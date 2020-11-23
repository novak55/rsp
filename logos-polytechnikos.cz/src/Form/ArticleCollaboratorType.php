<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCollaboratorType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$options['maping'] = isset($options['maping']) ?? false;
		$options['buttonAddCollaboratorDisabled'] = $options['buttonAddCollaboratorDisabled'] ?? false;

		$builder->setMethod('POST')
			->add('degreeBefore', TextType::class, [
				'label' => 'Titul před',
				'mapped' => $options['maping'],
				'attr' => [
					'maxlength' => 35,
				],
				'help' => 'Zadejte tituly uváděné před jménem. Maximální délka je 35 znaků.',
				'required' => false,
			])
			->add('nameCollaborator', TextType::class, [
				'label' => 'Spoluautor',
				'mapped' => $options['maping'],
				'attr' => [
					'maxlength' => 255,
				],
				'help' => 'Zadejte všechna jména příjmení. Maximální délka je 255 znaků.',
				'required' => false,
			])
			->add('degreeAfter', TextType::class, [
				'label' => 'Titul za',
				'mapped' => $options['maping'],
				'attr' => [
					'maxlength' => 30,
				],
				'help' => 'Zadejte tituly uváděné za jménem. Maximální délka je 30 znaků.',
				'required' => false,
			])
			->add('email', EmailType::class, [
				'mapped' => $options['maping'],
				'required' => false,
				'help' => 'Zadejte e-mail spoluautora.',
			]);
		if ($options['buttonAddCollaboratorDisabled'] !== false) {
			return;
		}

		$builder->add('addCollaborator', SubmitType::class, [
			'label' => 'Přidat',
			'attr' => [
				'class' => 'btn btn-primary btn-sm',
				'title' => 'Uloží článek a přidá spouautora',
			],
		]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'buttonAddCollaboratorDisabled' => false,
			'maping' => false,
		]);
	}

}
