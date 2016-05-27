<?php
class Token
{
    private $lexem;
    private $vrstica;
    private $kolona;
    private $tip;	#number, 1-17 from the automat table
    private $eof;	#true or false if it's end of file

	#CONSTRUCTOR
    public function __construct($l, $v, $k, $t, $e) {
        $this->lexem = $l;
        $this->vrstica = $v;
        $this->kolona = $k;
        $this->tip = $t;
        $this->eof = $e;
    }
	
	#SET AND GET METHODS
	
    public function SetToken($l, $v, $k, $t, $e)
    {
        $this->lexem = $l;
        $this->vrstica = $v;
        $this->kolona = $k;
        $this->tip = $t;
        $this->eof = $e;
    }

    public function getEof()
    {
        return $this->eof;
    }

    public function getVrstica()
    {
        return $this->vrstica;
    }

    public function getKolona()
    {
        return $this->kolona;
    }

    public function getLexem()
    {
        return $this->lexem;
    }

    public function getToken()
    {
        return $this->tip;
    }

    public function getTip()
    {
        return $this->tip;
    }

    public function toString()
    {
        $prikaz = "Lexem " . $this->lexem . " Tip: " . $this->tip . " Vrstica: ". $this->vrstica . " Kolona: " .$this->kolona ."\n";
        echo $prikaz;
    }
}

class Scanner
{
    private $row;
    private $column;
	private $rank;		#counter which will make sure that fen doesn't have more than 7 '/' (that it doesn't have more than 8 rows)
    private $booliscorrect; #true if the fen string is correct, false if it isn't
	private $list = array();
    private $automat =array();
    private $koncna = array();

    public function __construct($membersc) {
        $this->row = 0;
        $this->column = 0;
		$this->rank = 7;
		$this->booliscorrect = true;
        $this->list = $membersc;

        $this->initAvtomat();
    }

    public function initAvtomat()
    {
        for($i=0; $i<18; $i++)
        {
            for($j=0; $j<256; $j++)
            {
                $this->automat[$i][$j] = -1;
            }
        }

        for($i=0; $i<18; $i++)
        {
            $this->koncna[$i] = 18;
        }

        for ($i = ord ('0'); $i < ord ('9'); $i++)
        {
            $this->automat[0][$i] = 1;
        }
        $this->automat[0][ord ('r')] = 2;
        $this->automat[0][ord ('n')] = 3;
        $this->automat[0][ord ('b')] = 4;
        $this->automat[0][ord ('q')] = 5;
        $this->automat[0][ord ('k')] = 6;
        $this->automat[0][ord ('p')] = 7;
        $this->automat[0][ord ('R')] = 8;
        $this->automat[0][ord ('N')] = 9;
        $this->automat[0][ord ('B')] = 10;
        $this->automat[0][ord ('Q')] = 11;
        $this->automat[0][ord ('K')] = 12;
        $this->automat[0][ord ('P')] = 13;

        $this->automat[0][ord ('/')] = 14;
        $this->automat[0][ord (' ')] = $this->automat[0][ord ('\n')] = $this->automat[0][ord ('\t')] =15;

        $this->automat[15][ord ('w')] = 16;
        $this->automat[15][ord ('b')] = 17;

        for($i=1; $i<=14; $i++)
        {
            $this->koncna[$i] = $i;
        }
        $this->koncna[16] = 16;
        $this->koncna[17] = 17;

    }

    private function EOF()
    {
        if ($this->row == sizeof($this->list))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function readChar()
    {
        $tmp = $this->list[$this->row][$this->column];
        return $tmp;
    }

    private function getNextState($currentStateNS, $char)
    {	
        if(isset($currentStateNS))
        {
            if(isset($char))
            {
					$nextState = $this->automat[$currentStateNS][ord($char)];
					return $nextState;
            }
            else echo "unset char in getNextState" . "<br>";
        }
        else echo "unset currentState in getNextState" . "<br>";
    }

    private function read()
    {
        $znak = $this->list[$this->row][$this->column];
        if($this->column == strlen($this->list[$this->row])-1)
        {
            $this->row++;
            $this->column=0;
			$this->rank = 7;
        }
        else
            $this->column++;

        return $znak;
    }

    private function isKoncnoStanje($currentKS)
    {
        if($this->koncna[$currentKS] != 18)
        {
            return true;
        }
        else
            return false;
    }

    private function getKoncnoStanje($currentStateKS)
    {
        return $this->koncna[$currentStateKS];
    }
	
	private function izpisiNapako()
	{
		echo "Error. Fen string has more that 8 rows. <br>";
	}
	
	public function isCorrect()
	{
		return $this->booliscorrect;
	}

    public function nextToken()
    {
        $currentState = 0;
        $lexem = "";
        $startColumn = $this->column;
        $startRow = $this->row;

        do{
			$tmpState = -1;
				
			if($this->EOF() == false)
			{
				$tmpState = $this->getNextState($currentState, $this->readChar());
			}

			if($tmpState != -1)
			{
				$currentState = $tmpState;
				$lexem = $this->read();
			}

			else
			{
				if ($this->isKoncnoStanje($currentState))
				{
					$token = new Token($lexem, $startRow, $startColumn, $this->getKoncnoStanje($currentState), $this->EOF());
					if($token->getToken() == 14)
					{
						$this->rank--;
						if($this->rank<0)
						{
							$this->izpisiNapako();
							$this->booliscorrect = false;
						}
					}
					return $token;
				}
				else
				{
					$tmpToken = new Token("", $startRow, $startColumn, 18, $this->EOF());
					$this->booliscorrect = false;
					echo "Napaka v token. Vrstica: " . $tmpToken->getVrstica() . " Kolona: " . $tmpToken->getKolona() . ". <br>";
					return $tmpToken;
				}
			}
			
        }while(true);
    }

}

    $file = fopen("fen.txt", "r");
    $members = array();

    while (!feof($file)) {
        $members[] = trim(fgets($file));
    }
	
	echo $members[0]."</br>"; //fen string
    $scanner = new Scanner($members);
	if($scanner->isCorrect()){
		for($m=0; $m<strlen($members[0]); $m++) //string length ->43
		{
			$scanner->nextToken()->toString();
			print "<br>";
		}
		if($scanner->isCorrect()){
			echo "correct";
		}
		else{
			echo "bla";
		}
	}
	else{
		echo "bla";
	}
	

