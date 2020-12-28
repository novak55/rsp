<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('text', TextareaType::class, [
				'label' => 'Text komentáře',
				'required' => true,
				'constraints' => [
					new NotBlank([
						'message' => 'Zadejte text komentáře',
					]),
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit komentář',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Uložit komentář',
				],
			]);
	}

}
