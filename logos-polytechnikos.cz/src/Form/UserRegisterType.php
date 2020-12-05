<?php declare(strict_types = 1);

namespace App\Form;

use App\Security\SecurityService;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserRegisterType extends AbstractType
{

	/** @var SecurityService */
	private $securityService;

	/**
	 * UserRegisterType constructor.
	 */
	public function __construct(SecurityService $securityService)
	{
		$this->securityService = $securityService;
	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->setMethod('POST');

		if($this->securityService->hasRole('ROLE_REDAKTOR'))
		{
			$builder->add('rolePlainText', ChoiceType::class, [
				'choices' => array(
					'Redaktor' => 'ROLE_REDAKTOR',
					'Recenzent' => 'ROLE_RECENZENT',
					'Šéfredaktor' => 'ROLE_SEFREDAKTOR'
				)
			]);
		}
		else{

		}
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
				'first_options' => ['label' => 'Heslo'],
				'second_options' => ['label' => 'Zopakujte heslo'],
				'constraints' => [
					new Length(['min' => 4]),
					new NotBlank(),
				],
			])
			->add('doRegister', SubmitType::class, [
				'label' => 'Registrovat',
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
