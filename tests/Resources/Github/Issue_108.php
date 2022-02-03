<?php

namespace Translation\Extractor\Tests\Resources\Github;

class FooContoller
{
  public function index()
  {
      $this->getFlashBag()->add('success', 'issue_108');
  }
}
