<?php


namespace App\Controller\Test;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function foo()
    {
        $foo = [
            ['label' => $this->get('translator')->trans('this.should.be.translated.without.errors')], //ERROR: Form label is not a scalar string
            [/** @Ignore */'label' => $this->get('translator')->trans('this.should.be.translated.without.errors.and.now.it.works')],
            ['label_' => $this->get('translator')->trans('this.works.correctly')],
            ['label' => $this->shouldBeIgnored()], //ERROR: Form label is not a scalar string
            [/** @Ignore */'label' => $this->shouldBeIgnoredAndGoesWithoutError()],
        ];
    }

    private function shouldBeIgnored()
    {
    }

    private function shouldBeIgnoredAndGoesWithoutError()
    {
    }
}
