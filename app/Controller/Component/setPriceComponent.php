<?php

/**
 * retriving data from api.direct.yandex.ru via ajax
 *
 * @author kondrat
 */
class setPriceComponent extends Component {

    /**
     *
     * @param sting $method
     * @param array $params
     * @return json 
     */
    //two % to print "%"leteral
    public $modes = array(
        'Guaranty' => array(
            array(
                "name" => "minMinusC",
                "desc" => "Guaranty - %s cent",
                "priceType" => "min"
            ),
            array(
                "name" => "minPlusP",
                "desc" => "Guaranty + %s%%",
                "priceType" => "min"
            ),
            array(
                "name" => "minPlusC",
                "desc" => "Guaranty + %s cent",
                "priceType" => "min"
            )
        ),
        'GuarantyMax' => array(
            array(
                "name" => "maxMinusP",
                "desc" => "GuarantyMax - %s%%",
                "priceType" => "max"
            ),
            array(
                "name" => "maxMinusC",
                "desc" => "GuarantyMax - %s cent",
                "priceType" => "max"
            ),
            array(
                "name" => "maxPlusP",
                "desc" => "GuarantyMax + %s%%",
                "priceType" => "max"
            ),
            array(
                "name" => "maxPlusC",
                "desc" => "GuarantyMax + %s cent",
                "priceType" => "max"
            )
        ),
        'PremiumMin' => array(
            array(
                "name" => "premMinMinusP",
                "desc" => "PremiumMin - %s%%",
                "priceType" => "premMin"
            ),
            array(
                "name" => "premMinMinusC",
                "desc" => "PremiumMin - %s cent",
                "priceType" => "premMin"
            ),
            array(
                "name" => "premMinPlusP",
                "desc" => "PremiumMin + %s%%",
                "priceType" => "premMin"
            ),
            array(
                "name" => "premMinPlusC",
                "desc" => "PremiumMin + %s cent",
                "priceType" => "premMin"
            )
        ),
        'PremiumMax' => array(
            array(
                "name" => "premMaxMinusP",
                "desc" => "PremiumMax - %s%%",
                "priceType" => "premMax"
            ),
            array(
                "name" => "premMaxMinusC",
                "desc" => "PremiumMax - %s cent",
                "priceType" => "premMax"
            ),
            array(
                "name" => "premMaxPlusP",
                "desc" => "PremiumMax + %s%%",
                "priceType" => "premMax"
            ),
            array(
                "name" => "premMaxPlusC",
                "desc" => "PremiumMax + %s cent",
                "priceType" => "premMax"
            )
        )
    );

    function setPrice($method=NULL, $x= NULL, $min=NULL, $max=NULL, $premMin=NULL, $premMax=NULL, $maxPrice=NULL) {

        switch ($method) {
            case 'minMinusC':
                $resPrice = $min - $x / 100;
                break;
            case 'minPlusP':
                $resPrice = $min / 100 * $x + $min;
                break;
            case 'minPlusC':
                $resPrice = $min + $x / 100;
                break;


            case 'maxMinusP':
                $resPrice = $max - $max / 100 * $x;
                break;
            case 'maxMinusC':
                $resPrice = $max - $x / 100;
                break;
            case 'maxPlusP':
                $resPrice = $max / 100 * $x + $max;
                break;
            case 'maxPlusC':
                $resPrice = $max + $x / 100;
                break;


            case 'premMinMinusP':
                $resPrice = $premMin - $premMin / 100 * $x;
                break;
            case 'premMinMinusC':
                $resPrice = $premMin - $x / 100;
                break;
            case 'premMinPlusP':
                $resPrice = $premMin / 100 * $x + $premMin;
                break;
            case 'premMinPlusC':
                $resPrice = $premMin + $x / 100;
                break;


            case 'premMaxMinusP':
                $resPrice = $premMax - $premMax / 100 * $x;
                break;
            case 'premMaxMinusC':
                $resPrice = $premMax - $x / 100;
                break;
            case 'premMaxPlusP':
                $resPrice = $premMax / 100 * $x + $premMax;
                break;
            case 'premMaxPlusC':
                $resPrice = $premMax + $x / 100;
                break;


            default :
                $resPrice = 0;
                break;
        }


        if ($maxPrice != NULL) {
            if ($resPrice > $maxPrice) {
                $resPrice = $maxPrice;
            }
        }

        return $resPrice;
    }

}

?>
