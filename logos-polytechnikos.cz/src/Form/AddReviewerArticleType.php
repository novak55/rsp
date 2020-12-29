<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\Review;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddReviewerArticleType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->setMethod('POST')
			->add('reviewer', EntityType::class, [
				'class' => User::class,
				'label' => 'Jméno',
				'empty_data' => 'Není dostupný žádný recenzent!',
				'choice_label' => static function (User $user) {
					return $user->getFullNameByName();
				},
				'attr' => [
					'class' => 'select2',
				],
				'required' => true,
				'query_builder' => static function (EntityRepository $er) use ($options) {
					return $er->createQueryBuilder('u')
						->join('u.role', 'r')
						->where('r.id = 4')
						->andWhere('u.id not in (SELECT IDENTITY(s.reviewer) FROM ' . Review::class . ' s WHERE s.article = :article)')
						->setParameter('article', $options['article'])
						->orderBy('u.surname');
				},
			])
			->add('addReviewer', SubmitType::class, [
				'label' => 'Přidat',
				'attr' => [
					'class' => 'btn btn-primary btn-sm',
					'title' => 'Přidání recenzenta k článku',
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'article' => [],
		]);
	}

}
