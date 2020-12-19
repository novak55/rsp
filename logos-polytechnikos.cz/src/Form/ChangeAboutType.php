<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangeAboutType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('about', TextareaType::class, [
				'required' => false,
				'attr' => [
					'class' => 'tinymce',
					'rows' => '23',
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Vyplňte informace o časopise.',
					]),
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Upravit informace',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Upravit informace',
				],
			]);
	}

}
