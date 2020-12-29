<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TemeManagementType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('theme', TextType::class, [
				'required' => true,
				'label' => 'Téma časopisu',
				'constraints' => [
					new NotBlank([
						'message' => 'Vyplňte téma.',
					]),
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit téma',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Uložit téma',
				],
			]);
	}

}
