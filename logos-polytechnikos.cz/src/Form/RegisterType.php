<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->setMethod('POST')
            /*->add('title', TextType::class, [
                'label' => 'Titul',
                'attr' => [
                    'maxlength' => 100,
                ],
                'required' => true,
            ])*/
			->add('name', TextType::class, [
				'label' => 'Jméno',
				'attr' => [
					'maxlength' => 100,
				],
				'required' => true,
			])
			->add('surname', TextType::class, [
				'label' => 'Příjmení',
				'attr' => [
					'maxlength' => 100,
				],
				'required' => true,
			])
            ->add('username', TextType::class, [
                'label' => 'Přihlašovací jméno',
                'attr' => [
                    'maxlength' => 100,
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'maxlength' => 100,
                ],
                'required' => true,
            ])
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'invalid_message' => 'Hesla se neshodují, nebo nemají požadovanou složitost.',
				'required' => true,
				'first_options' => ['label' => 'Vaše heslo'],
				'second_options' => ['label' => 'Zopakujte heslo'],
				'constraints' => [
					new Length(['min' => 4]),
					new NotBlank(),
				],
			])
			->add('doRegister', SubmitType::class, [
				'label' => 'Registrovat',
				'attr' => [
					'class' => 'btn btn-primary'
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([]);
	}

}
