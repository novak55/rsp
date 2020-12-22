<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class MagazineStateType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('state', TextType::class, [
				'required' => true,
				'label' => 'Název číselníku stavu ',
				'constraints' => [
					new NotBlank([
						'message' => 'Vyplňte název číselníku stavu.',
					]),
				],
			])
			->add('submit', SubmitType::class, [
				'label' => 'Uložit stav',
				'attr' => [
					'class' => 'btn btn-primary btn-block',
					'title' => 'Uložit stav',
				],
			]);
	}

}
