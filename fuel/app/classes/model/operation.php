<?php

class Model_Operation extends Orm\Model{
  protected static $_properties = array(
    'id_site',
    'id_user',
    'nom_op',
    'a_revoir',
    'annee',
    'id_commune',
    'adresse',
    'X',
    'Y',
    'id_organisme',
    'id_type_op',
    'EA',
    'OA',
    'patriarche',
    'numero_operation',
    'arrete_prescription',
    'responsable_op',
    'anthropologue',
    'paleopathologiste',
    'etude_paleopathologique',
    'bibliographie'
  );
}
