<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EventSpotExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('time_ago', [$this, 'timeAgo']),
            new TwigFilter('price_format', [$this, 'priceFormat']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('capacity_badge', [$this, 'capacityBadge'], ['is_safe' => ['html']]),
        ];
    }

    // ⏳ time_ago
    public function timeAgo(\DateTimeInterface $date): string
    {
        $now = new \DateTime();
        $diff = $now->getTimestamp() - $date->getTimestamp();

        $minutes = floor($diff / 60);
        $hours = floor($diff / 3600);
        $days = floor($diff / 86400);
        $months = floor($days / 30);

        if ($months > 0) return "il y a $months mois";
        if ($days > 0) return "il y a $days jour(s)";
        if ($hours > 0) return "il y a $hours heure(s)";
        if ($minutes > 0) return "il y a $minutes minute(s)";

        return "à l'instant";
    }

    // 💰 price_format
    public function priceFormat(?float $price): string
    {
        if ($price === null || $price == 0) {
            return "Gratuit 🎉";
        }

        return number_format($price, 2, ',', ' ') . " €";
    }

    // 📊 capacity_badge
    public function capacityBadge(int $inscrits, int $capacite): string
    {
        if ($capacite <= 0) {
            return '<span class="badge bg-secondary">Indisponible</span>';
        }

        $percent = ($inscrits / $capacite) * 100;

        if ($percent < 50) {
            return '<span class="badge bg-success">Places disponibles</span>';
        }

        if ($percent < 80) {
            return '<span class="badge bg-warning text-dark">Places limitées</span>';
        }

        if ($percent < 100) {
            return '<span class="badge bg-danger">Dernières places</span>';
        }

        return '<span class="badge bg-dark">Complet</span>';
    }
}