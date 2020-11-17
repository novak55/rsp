<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddArticleType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'Název článku',
				'required' => true,
				'attr' => [
					'maxlength' => 255,
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Zadejte název článku',
					]),
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit',
				'attr' => [
					'class' => 'btn btn-primary btn-sm',
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([]);
	}

}
