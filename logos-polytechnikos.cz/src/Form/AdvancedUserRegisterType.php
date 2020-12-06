<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\UserRole;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class AdvancedUserRegisterType extends UserRegisterType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('role', EntityType::class, [
			'class' => UserRole::class,
			'choice_label' => 'description',
			'label' => 'Role',
			'required' => true,
			'mapped' => true,
			'query_builder' => static function (EntityRepository $er) {
				return $er->createQueryBuilder('r')
					->where('r.id > 2')
					->orderBy('r.description');
			},
		]);
	}

}
