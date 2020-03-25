<?php
namespace App\Models;

class Production {
        public $title;
        public $type;
        public $sites = array();

        function __construct($listing) {
            $this->title = $listing->attributes->Title;
            $this->type = $this->getProductionType($listing->attributes->Type);
            $this->add_site($listing);
          }

        function add_site($listing) {
            $shootDate = $listing->attributes->ShootDate/1000;
            $shootDtObj = new \DateTime();
            $shootDtObj->setTimestamp($shootDate);
            //use key to filter duplicate sites
            $shootDtKey =  $shootDtObj->format('mdY');

            $newSite= (object) [
                'name' => $listing->attributes->Site,
                'shoot_date' => $shootDtObj->format('F jS, Y')
            ];
          $this->sites[$shootDtKey] = $newSite;
        }

        function getProductionType(string $type){
            $result = "other";
            $type = strtolower($type);
            if (strpos($type,"tv") !== false){
                $result = "tv";
            }elseif (strpos($type,"movie") !== false){
                $result = "movie";
            }
            return $result;
        }

      }
      ?>
