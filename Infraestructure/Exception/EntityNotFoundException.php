<?php
namespace Infraestructure\Exception;

use RuntimeException;

final class EntityNotFoundException extends RuntimeException
{
  private $entity;
  private $params;

  public function __construct(String $entity, array $params)
  {
    $this->entity = $entity;
    $this->params = $params;
  }

  public function getErrorMessage()
  {
    $message = "Not found $this->entity with parameters: ";
    foreach($this->params as $key=>$param) {
      $message .= " $key = $param ";
    }
    return $message;
  }
}