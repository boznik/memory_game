<?php

namespace Drupal\memory_game\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class MemoryGameController extends ControllerBase {

  /**
   * @return JsonResponse
   */
  public function index() {
    $rows = \Drupal::request()->query->get('rows');
    $columns = \Drupal::request()->query->get('columns');
    return new JsonResponse($this->getData($rows, $columns));
  }

  /**
   * @return array
   */
  public function getData($rows, $columns) {

//    $alpha = [
//      'A',
//      'B',
//      'C',
//      'D',
//      'E',
//      'F',
//      'G',
//      'H',
//      'I',
//      'J',
//      'K',
//      'L',
//      'M',
//      'N',
//      'O',
//      'P',
//      'Q',
//      'R',
//      'S',
//      'T',
//      'U',
//      'V',
//      'W',
//      'X',
//      'Y',
//      'Z'
//    ];
//    $alpha = [];
//    foreach (range('A', 'Z') as $letter) {
//      $alpha[] = $letter;
//    }
    $alpha = range('A','Z', 1);

    if (isset($rows) && isset($columns)) {
      if ($rows > 6 || $columns > 6) {
        return t('Rows and coluumns cannot be greater than 6');
      }
      elseif ($rows < 1 || $columns < 1) {
        return t('Rows and coluumns cannot be less than 1');
      }
      elseif (($rows % 2 != 0) && ($columns % 2 != 0)) {
        return t('One value must be even');
      }
      $cardCount = $rows * $columns;
      $uniqueCardCount = $cardCount / 2;
      $cards = array_intersect_key( $alpha, array_flip( array_rand( $alpha, $uniqueCardCount ) ) );
      $uniqueCards = [];
      foreach($cards as $card) {
        $uniqueCards[] = $card;
      }
      $merge = array_merge($uniqueCards, $uniqueCards);
      shuffle($merge);
      $cardSubset = array_chunk($merge, $columns);
      $result = ['cards' => $cardSubset];
      $metadata = [
        'success' => 'true',
        'cardCount' => $cardCount,
        'uniqueCardCount' => $uniqueCardCount,
        'uniqueCards' => $uniqueCards,
        //'rows' => $rows,
        //'columns' => $columns
      ];
      $result = ['meta' => $metadata, 'data' => $result];
      return $result;
    }
    else {
      return t('Need to provide rows and columns in query string');
    }
  }

}
