<?php

namespace App\Lib\Form;

use App\Services\SearchFormObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('songName', TextType::class)
            ->add('artistName', TextType::class)
            ->add('songList', ChoiceType::class, [
                'choices' => SearchFormObject::getInstance()->getSongList()
            ])
            ->add('submit', SubmitType::class);
    }
}
