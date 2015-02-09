<?php
/**
 * Created by PhpStorm.
 * User: costa
 * Date: 06.02.15
 * Time: 13:24
 */

namespace rico\yii2stars;

use yii\base\Behavior;
use rico\yii2stars\RicoRating;
use yii;

class StarsBehaviour extends Behavior {

    const SESSION_ID = 'rico_session_rating_id';

    public $ratingMinimum = 0;
    public $ratingMaximum = 5;



    protected $error = null;

    public function checkIp($ip)
    {
        $stars = RicoRating::find()->where([
                'ip' =>$ip,
            ]
        )
            ->andWhere(['>', 'created', date("Y-m-d H:i:s", strtotime('1 day ago'))])
            //->createCommand()->rawSql;
            ->orderBy(['created' => SORT_DESC])
            ->all();
        if(count($stars)>5){
            $lastTime = $stars[0]->created;
            $nextTime = date('d.m.Y, H:i:s', strtotime($lastTime)+60*60*24);
            //$nextTime = date('%A, %e %m %G %H:%M', strtotime($lastTime)+60*60*24);
            $this->setRaitingError('За последние сутки с этого IP адреса уже были созданы оценки более 5 раз. Извините, следущий раз вы сможете голосовать '.$nextTime);
            return false;
        }

        return true;
    }

    private function findObjInSession()
    {

    }
    public function checkPerson()
    {
        $votes = Yii::$app->session->get(self::SESSION_ID, []);
        foreach($votes as $vote){
            if($vote['itemId']==$this->owner->id){
                $this->setRaitingError('Недавно Вы уже оценивали этот объект, хотите изменить свою оценку? <a id="rico-rating-change-star" href="#">Изменить оценку</a>');
                return false;
            }
        }
        return true;
    }


    public function setRating($value, $anyway=false){
        if($value < $this->ratingMinimum && $value > $this->ratingMaximum){
            $this->setRaitingError('Значение рейтинга выходит за пределы шкалы.');
            return false;
        }

        if(!$this->owner->id){
            $this->setRaitingError('Owner must have id');
            return false;
        }

        if(!$anyway){
            if(!$this->checkPerson()){
                return false;
            }
        }


        //Пеерезаписать оценку?
        if(!$anyway){//нет
            if(!$this->checkIp($_SERVER['REMOTE_ADDR'])){
                return false;
            }

            $star = new RicoRating;
            $star->itemClass = $this->owner->className();
            $star->value = $value;
            $star->itemId = $this->owner->id;
            $star->ip = $_SERVER['REMOTE_ADDR'];


        }else{//да
            $star = RicoRating::findOne(['itemClass'=>$this->owner->className(), 'itemId'=>$this->owner->id]);
            if(!$star){
                return true;
            }
            $star->itemClass = $this->owner->className();
            $star->value = $value;
            $star->itemId = $this->owner->id;
            $star->ip = $_SERVER['REMOTE_ADDR'];

        }

        if(Yii::$app->user->id){
            $star->userId = Yii::$app->user->id;

            
        }

        if(!$star->save()){
            $this->setRaitingError(print_r($star->getErrors(), true));
            return false;
        }

        //Записываем в сессию
        $votes = Yii::$app->session->get(self::SESSION_ID, []);
        $votes[] = ['itemId'=>$this->owner->id];
        Yii::$app->session->set(self::SESSION_ID, $votes);
        return true;
    }

    public function isStarred()/** for this user*/
    {
        return true;
    }

    private function setRaitingError($error)
    {
        $this->error = $error;
    }

    public function getRaitingError()
    {
        return $this->error;
    }

    public function getRicoRating()
    {
       /* p($this->owner->id);
        p($this->owner->className());die;*/

        $stars = RicoRating::find()->where([
            'itemClass' => $this->owner->className(),
            'itemId' => $this->owner->id
        ])->all();
        //p(count($stars));die;
        if(count($stars)==0){
            return 0;
        }
        $summ = 0;
        foreach($stars as $star){
            $summ += $star->value;
        }

        return round($summ/count($stars));
    }
}