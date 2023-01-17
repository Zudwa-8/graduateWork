<?php
    $connectDatabase = mysqli_connect('localhost', 'root', '', 'bd');

    if (!$connectDatabase) {
        mysqli_error($link);
    }

    function sqlConvert(mysqli_result $result){
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }

     function array_output($array){
        echo "<pre>\n";
        var_dump($array);
        echo '</pre>';
    }
    
    function convertBBCode($string){
        $html = array("<b>", "<i>", "<u>", "</b>", "</i>", "</u>", "<br>");
        $bbcode = array("[b]", "[i]", "[u]", "[/b]", "[/i]", "[/u]", "\n");
        return str_replace($html, $bbcode, $string);
    }

    function readNumber($num, $depth=0)
    {
        $num = (int)$num;
        $retval ="";
        if ($num < 0) // if it's any other negative, just flip it and call again
            return "negative " + readNumber(-$num, 0);
        if ($num > 99) // 100 and above
        {
            if ($num > 999) // 1000 and higher
                $retval .= readNumber($num/1000, $depth+3);
    
            $num %= 1000; // now we just need the last three digits
            if ($num > 99) // as long as the first digit is not zero
                $retval .= readNumber($num/100, 2)." hundred\n";
            $retval .=readNumber($num%100, 1); // our last two digits                       
        }
        else // from 0 to 99
        {
            $mod = floor($num / 10);
            if ($mod == 0) // ones place
            {
                if ($num == 1) $retval.="one";
                else if ($num == 2) $retval.="two";
                else if ($num == 3) $retval.="three";
                else if ($num == 4) $retval.="four";
                else if ($num == 5) $retval.="five";
                else if ($num == 6) $retval.="six";
                else if ($num == 7) $retval.="seven";
                else if ($num == 8) $retval.="eight";
                else if ($num == 9) $retval.="nine";
            }
            else if ($mod == 1) // if there's a one in the ten's place
            {
                if ($num == 10) $retval.="ten";
                else if ($num == 11) $retval.="eleven";
                else if ($num == 12) $retval.="twelve";
                else if ($num == 13) $retval.="thirteen";
                else if ($num == 14) $retval.="fourteen";
                else if ($num == 15) $retval.="fifteen";
                else if ($num == 16) $retval.="sixteen";
                else if ($num == 17) $retval.="seventeen";
                else if ($num == 18) $retval.="eighteen";
                else if ($num == 19) $retval.="nineteen";
            }
            else // if there's a different number in the ten's place
            {
                if ($mod == 2) $retval.="twenty ";
                else if ($mod == 3) $retval.="thirty ";
                else if ($mod == 4) $retval.="forty ";
                else if ($mod == 5) $retval.="fifty ";
                else if ($mod == 6) $retval.="sixty ";
                else if ($mod == 7) $retval.="seventy ";
                else if ($mod == 8) $retval.="eighty ";
                else if ($mod == 9) $retval.="ninety ";
                if (($num % 10) != 0)
                {
                    $retval = rtrim($retval); //get rid of space at end
                    $retval .= "-";
                }
                $retval.=readNumber($num % 10, 0);
            }
        }
    
        if ($num != 0)
        {
            if ($depth == 3)
                $retval.=" thousand\n";
            else if ($depth == 6)
                $retval.=" million\n";
            if ($depth == 9)
                $retval.=" billion\n";
        }
        return $retval;
    }

    $month_name = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря']; 
?>