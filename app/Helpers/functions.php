<?php

// Fonction couleur pour les statuts des commandes
function getStatusColor($status) 
{
    return match ($status) {
        'pending'          => 'warning text-dark',
        'accepted'         => 'info text-white',
        'preparing'        => 'primary',
        'shipping'         => 'info text-white',
        'delivered'        => 'success text-white',
        'waiting_material' => 'danger text-white',
        'finished'         => 'secondary text-white',
        'cancelled'        => 'dark text-white',
        default            => 'light text-dark',
    };
}