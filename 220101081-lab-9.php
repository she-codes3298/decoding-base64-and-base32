<?php
 $str = isset($_GET["str"]) ? $_GET["str"] : "";
$code = isset($_GET["encode_opt"]) ? $_GET["encode_opt"] : "";
echo "STEP 1<br>The original string passed is:<br>"." ".$str."<br> Decoding scheme opted is:<br>"." ".$code."<br>";

$str2="";
for($i=0;$i<strlen($str);$i++)
{ 
  if($str[$i]!=('='))
  {
    global $str2;
    $str2.=$str[$i];
  }
  else
  {
   break;
  }
}
echo "<br>STEP 2<br> string after the padding is removed:<br> "." ".$str2;
$bin="";
$bin2="";
$base32_dec=[];
$base64_enc = [];
function binaryToDecimal($d2)
{
    $sum = 0;
    $n = strlen($d2) - 1;
    for ($i = $n; $i >= 0; $i--) {
        $sum += $d2[$i] * pow(2, ($n - $i));
    }
    //var_dump($sum);
    return $sum;
}
function binaryToChar($bin3)
{   echo "<br><br> STEP 5<br>The 8bits binary chunk is:<br> ";
    $s = ""; 
    $result = ""; 
    for ($i = 0; $i < strlen($bin3); $i++) {
         if(($i+1)%8!=0)
         {
           $s .= $bin3[$i];
         }
         else
         {
           $s .= $bin3[$i];
           echo $s." ";
           $result .= chr(binaryToDecimal($s)); 
           $s = "";
         }
    }
     echo "<br><br>STEP 6<br>And the final decoded string is: ".$result;
     
    return $result; // Return the accumulated characters
}

function decimalToBinary($d1,$n)
{
    $binary = ""; 
    while ($d1 != 0) {
        $r = $d1 % 2; 
        $binary .= $r; 
        $d1 = (int)($d1 / 2); 
    }
    
    if(strlen($binary) <$n)
    {
     
     $abc= $n - strlen($binary);
        for($i = 0; $i <$abc; $i=$i+1) {
  
            $binary .= '0';
        }
        
    }
    // Reverse the binary string
    $binary = strrev($binary);
    echo $binary.",";
    return $binary;
}


function mappingBase64($str2)
{
    global $base64_enc;$n=6;
    global $bin ; 
     echo " <br><br>STEP 3<br>index values and their corresponding binary code of the character in base64 index table is:<br> "; 
    for ($i = 0; $i < strlen($str2); $i++) {
        for ($j = 0; $j < count($base64_enc); $j++) {
            if ($str2[$i] == $base64_enc[$j]) {
                
                echo " ".$j."=";
                $bin .= decimalToBinary($j,$n);
            }
        }
    }
   echo "<br><br>STEP 4<br> The combined string is:<br> ".$bin; 
   return $bin;
}
function mappingBase32($str2)
{
 global $base32_dec;
 global $bin2;$n=5;
 
 echo " <br><br>STEP 3<br>index values and their corresponding binary code of the character in base32 index table is:<br> ";
 for ($i = 0; $i < strlen($str2); $i++) 
 {
        for ($j = 0; $j < count($base32_dec); $j++)
         {
            if ($str2[$i] == $base32_dec[$j])
             {  echo " ".$j."=";
                $bin2 .= decimalToBinary($j,$n);
            }
        }
    }
    echo "<br><br> STEP 4<br>The combined string is:<br> ".$bin2;
   return $bin2;
  }
 if($code=='base64')
 {
    $d=0;
   // Association base64
    $ch = 'A';
    global $bin;
    for ($i = 0; $i <=25; $i++) {
    $base64_enc[$i] = $ch;
    $ch++;
    }
   $ch2 = 'a';
    for ($i = 26; $i <= 51; $i++) {
    $base64_enc[$i] = $ch2;
    $ch2++;
    }
    $ch3 = 0;
    for ($i = 52; $i <=61; $i++) {
    $base64_enc[$i] = $ch3;
    $ch3++;
    }
    $base64_enc[62] = '+';
    $base64_enc[63] = '/'; 
     for($j=0;$j<strlen($str2);$j++)
    {
      if(ctype_lower($str2[$j]) || ctype_upper($str2[$j]) || is_numeric($str2[$j])  || $str2[$j]=='+' || $str2[$j]=='/' || $str2[$j]=='=')
        {  
          $d=1;
        }
        else
        {
         $d=0;
         break;
        }
       
     }
 if($d==1)
 {
    $bin=mappingBase64($str2);
    binaryToChar($bin);
 }
 else
 {
       echo "<br>Invalid input as some of the characters in the string do not match the base32 index table<br>";
       exit();
 }
   
    
}
 else
 {
 $d=0;
  //Association base32
   $ch4='A';
   
   for ($i = 0; $i <=25; $i++)
   {
    
    $base32_dec[$i] = $ch4;
    $ch4++;
   }
   $ch5=2;
   for ($i = 26; $i <=31; $i++)
   {
    $base32_dec[$i] = $ch5;
    $ch5++;
   }
    for($j=0;$j<strlen($str2);$j++)
    {
      if(ctype_lower($str2[$j]) || $str2[$j]=='0' || $str2[$j]=='1' || $str2[$j]=='8')
        {  global $d;
           $d=1;
           
            echo "<br>Invalid input as some of the characters in the string do not match the base32 index table<br>";
            break;
        }
     }
   if($d==0)
   {
   $bin2=mappingBase32($str2);
   binaryToChar($bin2);
   }
   else
   {
    exit();
   }
}
?>


