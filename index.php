<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<?php

    $aValues = null;
    $hAltSet = '';
    $hRandSet = " checked";
    $hCardSet = '';
    $nThrow = (isset($_POST['throw_num'])) ? $_POST['throw_num'] : 0;
    if ( $nThrow > 35 ) {
        $nThrow = 0;
        $_POST['throw_seed'] = time();
    }
    $nThrowSeed = time();
    
    if ( isset($_POST['type']) ) {
        if ( isset($_POST['throw_seed']) ) {
            $nThrowSeed = intval($_POST['throw_seed']);
        }
        
        if ('rand' == $_POST['type']) {
            $hRandSet = " checked";
            $hCardSet = "";
            $aValues = getRandomThrow();
        } else {
            $hRandSet = "";
            $hCardSet = " checked";
            $aValues = getCardThrow($nThrowSeed, $nThrow);
            $nThrow += 1;
        }
        
        if ( isset($_POST['alternate']) && 'alternate' == $_POST['alternate'] ) {
            $hAltSet = " checked";
            $temp = $hRandSet;
            $hRandSet = $hCardSet;
            $hCardSet = $temp;
        }
        
    }
    
    function getRandomThrow() {
        $aColorThrowOptions = array('black', 'black', 'black', 'green', 'yellow', 'blue');
        
        $colorThrow = $aColorThrowOptions[array_rand($aColorThrowOptions)];
        srand(time());
        $throw1 = rand(1, 6);
        $throw2 = rand(1, 6);
        
        return array( 'random throw', $colorThrow, $throw1, $throw2 );
    }
    
    function getCardThrow( $seed, $index ) {
        $aColors = array('black', 'black', 'black', 'green', 'yellow', 'blue');
        $aDie1 = array(1, 2, 3, 4, 5, 6);
        $aDie2 = array(1, 2, 3, 4, 5, 6);

        $aDeckColors = array();
        $aDeckDie1 = array();
        $aDeckDie2 = array();
        
        for ( $i=0; $i<6; $i++ ) {
            $aDeckColors = array_merge($aDeckColors, $aColors);
            $aDeckDie1 = array_merge($aDeckDie1, $aDie1);
            $aDeckDie2 = array_merge($aDeckDie2, $aDie2);
        }
        
        srand($seed);
        shuffle($aDeckColors);
        shuffle($aDeckDie1);
        shuffle($aDeckDie2);
        
        return array(
            'pick from the card deck',
            $aDeckColors[$index],
            $aDeckDie1[$index],
            $aDeckDie2[$index],
        );
    }

?>
<html lang='en'>
    <head>
        <title>Neil's Magical Dice Roller</title>
        <meta name='viewport' content='width=device-width'>
        <style type='text/css'>
            fieldset {
                padding: 0;
                border: none;
            }

            legend {
                padding: 0;
                margin: 0;
            }
            
            input.submit {
                width: 100%;
                font-size: 200%;
            }

            .dice {
                overflow: hidden;
                zoom: 1;
            }
            
            .dice span {
                float: left;
                width: 90px;
                height: 90px;
                border: 1px solid black;
                margin-right: 13px;
                text-indent: -9999px;
                background: transparent url(d6.png) no-repeat scroll 0 0;
            }
            
            .dice .colors {
                background: transparent url(dice-bgby.png) no-repeat scroll 0 0;
            }
            
            .dice span.primary {
                background-color: red;
            }
            
            .dice span.secondary {
                background-color: pink;
                margin-right: 0;
            }
            
            .dice span.face_6 {
                background-position: 0 0;
            }
            .dice span.face_5 {
                background-position: -90px 0;
            }                      
            .dice span.face_4 {    
                background-position: -180px 0;
            }                      
            .dice span.face_3 {    
                background-position: -270px 0;
            }                      
            .dice span.face_2 {    
                background-position: -360px 0;
            }                      
            .dice span.face_1 {    
                background-position: -450px 0;
            }                      
            .dice span.face_black {
                background-position: 0 0;
            }                      
            .dice span.face_green {
                background-position: -90px 0;
            }                      
            .dice span.face_blue { 
                background-position: -180px 0;
            }                      
            .dice span.face_yellow {
                background-position: -270px 0;
            }
        </style>
    </head>
    <body>
        <?php
            if ( $aValues ) {
            
                echo "<p>This was a {$aValues[0]}.</p>";
                echo "<p class='dice'>
                        <span class='colors face_{$aValues[1]}'>{$aValues[1]}</span> 
                        <span class='primary face_{$aValues[2]}'>{$aValues[2]}</span> 
                        <span class='secondary face_{$aValues[3]}'>{$aValues[3]}</span>
                    </p>";
            
            }
        ?>

        <form method='post' action='#'>
            <fieldset>
                <legend>Throwing Options</legend>
                <p>
                    <input type='checkbox' name='alternate' id='alternate' value='alternate'<?php echo $hAltSet; ?>>
                    <label for='alternate'>Alternate throw types</label>
                </p>
                <fieldset>
                    <legend>Next Throw Type</legend>
                    <ul>
                    <li>
                        <input type='radio' name='type' id='type_rand' value='rand'<?php echo $hRandSet; ?>>
                        <label for='type_rand'>Random Throw</label>
                    </li>
                    <li>
                        <input type='radio' name='type' id='type_card' value='card'<?php echo $hCardSet; ?>>
                        <label for='type_card'>From Card Deck</label>
                    </li>
                    </ul>
                </fieldset>
                <p>
                    <input type='submit' class='submit' value='Throw Dice!'>
                    <input type='hidden' name='throw_num' value='<?php echo $nThrow; ?>'>
                    <input type='hidden' name='throw_seed' value='<?php echo $nThrowSeed; ?>'>
                </p>
            </fieldset>
        </form>
        
        <p>
            The "Card Deck" consists of three packs of cards.  The first pack
            contains 18 black, 6 green, 6 yellow and 6 blue options.  The
            other two packs both consist of six options each of the numbers
            1 to 6.
        </p>
        <p>
            The first time a throw from the card deck is requested, the three
            packs of cards are shuffled by the computer and set aside.  The 
            top card from each pack is then taken.  Every consequent time that
            a throw from the card deck is requested, the computer takes the
            next card from each pack.  Once all 36 cards from each pack have
            been exhausted the computer reshuffles them ready for the next
            throw from the card deck.
        </p>
        
    </body>
</html>