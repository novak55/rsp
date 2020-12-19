<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangeReviewManagementType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('reviewManagement', TextareaType::class, [
				'required' => false,
				'attr' => [
					'class' => 'tinymce',
					'rows' => '23',
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Vyplňte popis recenzního řízení.',
					]),
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Upravit popis',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Upravit popis',
				],
			]);
	}

}
