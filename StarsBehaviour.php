<?php
/**
 * Created by PhpStorm.
 * User: costa
 * Date: 06.02.15
 * Time: 13:24
 */

namespace rico\yii2stars;

use yii\base\Behavior;


class StarsBehaviour extends Behavior {

    public $ratingMinimum = 0;
    public $ratingMaximum = 5;

    public function setRating($value){
        //check value

        //try to create and save star

        //return star
    }

    public function isStarred()/** for this user*/
    {
        return true;
    }

    public function getAverageRating()
    {

    }
}