<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 06.05.19
 * Time: 19:48
 */

namespace Drupal\erwartungscheck\Helper;

use Drupal\taxonomy\Entity\Term;

class ErwartungscheckHelper {

  public function getErwartungcheck() {
    //get nids of content type erwartungscheck content type
    $nids = \Drupal::entityQuery('node')
      ->condition('type','erwartungscheck')->execute();

    //node objects from the nids
    $nodes =  node_load_multiple($nids);

    //create aussagen based on content
    foreach($nodes as $node) {
      $aussagen = $node->get('field_erwartungscheck_aussagen')->referencedEntities();
      foreach($aussagen as $aussage) {


        $aussagen_data[] = [
          'aussage' => $aussage->body->value,
          'rueckmeldung' => $aussage->field_rueckmeldung->value,
          'richtige_antwort' => $this->getTextOfTid($aussage->field_richtige_antwort->target_id),
          'gruppe' => $this->getTextOfTid($aussage->field_gruppe->target_id),
        ];
      };


    };

    return $aussagen_data;

  }

  public function getTextOfTid($tid) {
    $term = Term::load($tid);
    $name = $term->getName();

    return $name;
  }
}