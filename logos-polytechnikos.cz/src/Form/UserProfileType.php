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

class UserProfileType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('name', TextType::class, [
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
			->add('titleBeforeName', TextType::class, [
				'label' => 'Titul před jménem',
				'attr' => [
					'maxlength' => 40,
				],
				'required' => false,
			])
			->add('titleAfterName', TextType::class, [
				'label' => 'Titul za jménem',
				'attr' => [
					'maxlength' => 40,
				],
				'required' => false,
			])
			->add('email', EmailType::class, [
				'label' => 'Email',
				'attr' => [
					'maxlength' => 100,
				],
				'required' => true,
			])
			->add('doRegister', SubmitType::class, [
				'label' => 'Upravit profil',
				'attr' => [
					'class' => 'btn btn-primary',
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([]);
	}

}
