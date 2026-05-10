<?php
namespace App\Command;

use App\Repository\EvenementRepository;
use App\Repository\InscriptionRepository;
use App\Repository\LieuRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:eventspot:report',
    description: 'Génère un rapport sur les événements et inscriptions',
)]
class EventSpotReportCommand extends Command
{
    public function __construct(
        private EvenementRepository $eventRepo,
        private InscriptionRepository $inscRepo,
        private LieuRepository $lieuRepo,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('upcoming', null, InputOption::VALUE_NONE, 'Afficher uniquement les événements à venir')
            ->addOption('lieu', null, InputOption::VALUE_OPTIONAL, 'Filtrer par nom de lieu');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('📊 Rapport EventSpot');

        $evenements = $this->eventRepo->findAll();

        // Filtrer upcoming
        if ($input->getOption('upcoming')) {
            $now = new \DateTime();
            $evenements = array_filter($evenements, fn($e) => $e->getDateDebut() > $now);
            $io->note('Filtre : événements à venir uniquement');
        }

        // Filtrer par lieu
        if ($lieu = $input->getOption('lieu')) {
            $evenements = array_filter($evenements, fn($e) => $e->getLieu() && (
    stripos($e->getLieu()->getNom(), $lieu) !== false ||
    stripos($e->getLieu()->getVille(), $lieu) !== false
));
            $io->note("Filtre lieu : $lieu");
        }

        $evenements = array_values($evenements);

        // Stats par statut
        $statuts = [];
        foreach ($evenements as $e) {
            $statuts[$e->getStatut()] = ($statuts[$e->getStatut()] ?? 0) + 1;
        }
        $io->section('Événements par statut');
        $io->table(['Statut', 'Nombre'], array_map(fn($k, $v) => [$k, $v], array_keys($statuts), $statuts));

        // Stats inscriptions
        $eventIds = array_map(fn($e) => $e->getId(), $evenements);
$inscriptions = array_filter(
    $this->inscRepo->findAll(),
    fn($i) => in_array($i->getEvenement()->getId(), $eventIds)
);
        $inscStatuts = [];
        foreach ($inscriptions as $i) {
            $inscStatuts[$i->getStatut()] = ($inscStatuts[$i->getStatut()] ?? 0) + 1;
        }
        $io->section('Inscriptions par statut');
        $io->table(['Statut', 'Nombre'], array_map(fn($k, $v) => [$k, $v], array_keys($inscStatuts), $inscStatuts));

        // Taux de remplissage moyen
        $total = 0;
        foreach ($evenements as $e) {
            if ($e->getCapaciteMax() > 0) {
                $total += count($e->getInscriptions()) / $e->getCapaciteMax() * 100;
            }
        }
        $moy = count($evenements) > 0 ? round($total / count($evenements), 2) : 0;
        $io->note("Taux de remplissage moyen : $moy%");

        // Top 3
        usort($evenements, fn($a, $b) => count($b->getInscriptions()) - count($a->getInscriptions()));
        $io->section('Top 3 événements populaires');
        $top3 = array_slice($evenements, 0, 3);
        $io->table(['Titre', 'Inscrits'], array_map(fn($e) => [$e->getTitre(), count($e->getInscriptions())], $top3));

        // Revenu total
        $revenu = 0;
        foreach ($inscriptions as $i) {
            if ($i->getStatut() === 'confirmee' && $i->getEvenement()->getPrix()) {
                $revenu += $i->getEvenement()->getPrix();
            }
        }
        $io->success("Revenu total estimé : $revenu TND");

        return Command::SUCCESS;
    }
}