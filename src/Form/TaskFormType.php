<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TaskFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function checkRole(): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('title', TextType::class, ['label'=>'Заголовок' ])
            ->add('description', TextType::class, ['label'=>'Текст заявки' ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $security = $this->checkRole();
                if ($security)
                {
                    $form = $event->getForm();
                    $form->add('state', ChoiceType::class,
                        [
                        'choices' =>
                            [
                            'Новая' => 'Новая',
                            'В процессе' => 'В процессе',
                            'Отменена' => 'Отменена',
                            'Закончена' => 'Закончена',
                            ]
                        ]);
                }
            })
            ->add('save', SubmitType::class, ['label' => 'Создать заявку'])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}