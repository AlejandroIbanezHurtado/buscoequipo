<?php
  namespace App\EventSubscriber;
  use App\Entity\Jugador;
  use Doctrine\ORM\EntityManagerInterface;
  use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
  use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
  use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
  use Symfony\Component\EventDispatcher\EventSubscriberInterface;

  class EasyAdminSubscriber implements EventSubscriberInterface
  {

      private $entityManager;
      private $passwordEncoder;

      public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
      {
          $this->entityManager = $entityManager;
          $this->passwordEncoder = $passwordEncoder;
      }

      public static function getSubscribedEvents()
      {
          return [
              BeforeEntityPersistedEvent::class => ['addUser'],
              BeforeEntityUpdatedEvent::class => ['updateUser'],
          ];
      }

      public function updateUser(BeforeEntityUpdatedEvent $event)
      {
          $entity = $event->getEntityInstance();

          if (!($entity instanceof Jugador)) {
              return;
          }
          $this->setPassword($entity);
      }

      public function addUser(BeforeEntityPersistedEvent $event)
      {
          $entity = $event->getEntityInstance();

          if (!($entity instanceof Jugador)) {
              return;
          }
          $this->setPassword($entity);
      }

      /**
       * @param Jugador $entity
       */
      public function setPassword(Jugador $entity): void
      {
          $pass = $entity->getPassword();

          $entity->setPassword(
              $this->passwordEncoder->hashPassword(
                  $entity,
                  $pass
              )
          );
          $this->entityManager->persist($entity);
          $this->entityManager->flush();
      }

  }