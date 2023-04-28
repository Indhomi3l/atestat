<?php

namespace App\Controller\Admin;

use App\Entity\Lyric;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LyricCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lyric::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
