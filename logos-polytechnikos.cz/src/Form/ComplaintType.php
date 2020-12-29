<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\EvaluationLevel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ComplaintType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Text námitky',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Zadejte text námitky',
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Vložit námitku',
                'attr' => [
                    'class' => 'btn btn-primary btn-block',
                    'title' => 'Vložit článek',
                ],
            ]);
    }

}
