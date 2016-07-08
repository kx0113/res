<?php

namespace Common\ORG\Util\Wxpay;

class  SDKRuntimeException extends \Exception {

  public function errorMessage() {
    return $this->getMessage();
  }
}

?>