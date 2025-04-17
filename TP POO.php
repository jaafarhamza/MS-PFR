<?php
interface ReservableInterface
{
    function reserve(Client $client, DateTime $dateDebut, int $nbJours): Reservation;
}

abstract class Vehicule implements ReservableInterface
{
    protected int $id;
    protected string $immatriculation;
    protected string $marque;
    protected string $modele;
    protected float $prixJour;
    protected bool $disponible;

    public function __construct(int $id, string $immatriculation, string $marque, string $modele, float $prixJour)
    {
        $this->id = $id;
        $this->immatriculation = $immatriculation;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->prixJour = $prixJour;
        $this->disponible = true;
    }

    public function afficheDetails()
    {
        return "Véhicule: {$this->marque} {$this->modele}, Immatriculation: {$this->immatriculation}, Prix: {$this->prixJour}";
    }
    public function calculatePrix(int $jours): float
    {
        return $this->prixJour * $jours;
    }
    public function estDisponible(): bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): void
    {
        $this->disponible = $disponible;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrixJour(): float
    {
        return $this->prixJour;
    }

    abstract function getType(): string;
}
class Voiture extends Vehicule implements ReservableInterface
{
    public $nbPorte;
    public $transmission;

    public function __construct(
        int $id,
        string $immatriculation,
        string $marque,
        string $modele,
        float $prixJour,
        int $nbPorte,
        string $transmission
    ) {
        $this->id = $id;
        $this->immatriculation = $immatriculation;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->prixJour = $prixJour;
        $this->nbPorte = $nbPorte;
        $this->transmission = $transmission;
    }
    public function getNbPortes(): int
    {
        return $this->nbPorte;
    }
    
    public function getTransmission(): string
    {
        return $this->transmission;
    }
    function getType(): string
    {
        return 'voiture';
    }

    function afficheDetails()
    {
        return ", Nombre de portes: {$this->nbPorte}, Transmission: {$this->transmission}";
    }

    public function reserve(Client $client, DateTime $dateDebut, int $nbJours): Reservation
    {
        if (!$this->estDisponible()) {
            echo "La voiture pas disponible";
        }
        
        $this->setDisponible(false);
        
        $reservation = new Reservation($this, $client, $dateDebut, $nbJours);
        
        $client->ajouterReservation($reservation);
        
        return $reservation;
    }
}

class Moto extends Vehicule implements ReservableInterface
{
    protected $cylindree;

    public function __construct(
        int $id,
        string $immatriculation,
        string $marque,
        string $modele,
        float $prixJour,
        int $cylindree
    ) {
        $this->id = $id;
        $this->immatriculation = $immatriculation;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->prixJour = $prixJour;
        $this->cylindree = $cylindree;
    }

    public function getCylindree(): int
    {
        return $this->cylindree;
    }

    public function getType(): string
    {
        return 'moto';
    }

    public function reserve(Client $client, DateTime $dateDebut, int $nbJours): Reservation
    {
        if (!$this->estDisponible()) {
            echo "La moto pas disponible";
        }
        
        $this->setDisponible(false);
        
        $reservation = new Reservation($this, $client, $dateDebut, $nbJours);
        
        $client->ajouterReservation($reservation);
        
        return $reservation;
    }
}
class Camion extends Vehicule implements ReservableInterface
{
    protected float $capaciteTonnage;

    public function __construct(
        int $id,
        string $immatriculation,
        string $marque,
        string $modele,
        float $prixJour,
        float $capaciteTonnage
    ) {
        $this->id = $id;
        $this->immatriculation = $immatriculation;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->prixJour = $prixJour;
        $this->capaciteTonnage = $capaciteTonnage;
    }
    public function getCapaciteTonnage(): float
    {
        return $this->capaciteTonnage;
    }

    public function getType(): string
    {
        return 'camion';
    }
    

    public function reserve(Client $client, DateTime $dateDebut, int $nbJours): Reservation
    {
        if (!$this->estDisponible()) {
            echo "Le camoin pas disponible";
        }
        
        $this->setDisponible(false);
        
        $reservation = new Reservation($this, $client, $dateDebut, $nbJours);
        
        $client->ajouterReservation($reservation);
        
        return $reservation;
    }
}

abstract class Personne
{
    protected string $nom;
    protected string $prenom;
    protected $email;

    public function __construct(string $nom, string $prenom, string $email)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    abstract function afficheProfil(): string;
}

class Client extends Personne
{
    protected int $numeroClient;
    protected $reservation;

    public function __construct(string $nom, string $prenom, string $email, string $numeroClient, string $reservation )
    {
        $this->nom=$nom;
        $this->prenom=$prenom;
        $this->email=$email;
        $this->numeroClient = $numeroClient;
        $this->reservation=$reservation;
    }

    public function ajouterReservation(reservation $r) {
        $this->reservation=$r;
    }
    public function afficheProfil(): string {
        return "Client: {$this->prenom} {$this->nom}, Email: {$this->email}, " .
               "Client: {$this->numeroClient}";
    }

    public function getHistorique(): string
    {
        return $this->reservation;
    }
}

class Agence
{
  
    private string $nom;
    
    private string $ville;
    
    private array $vehicules;
    
    private array $clients;
    
    public function __construct(string $nom, string $ville)
    {
        $this->nom = $nom;
        $this->ville = $ville;
        $this->vehicules = [];
        $this->clients = [];
    }
    
    public function ajouterVehicule(Vehicule $vehicule): void
    {
        $this->vehicules[] = $vehicule;
        echo "Véhicule ajout: {$vehicule} {$vehicule} {$vehicule}";
    }
    
    public function rechercherVehiculeDisponible(string $type)
    {
        foreach ($this->vehicules as $vehicule) {
            if ($vehicule->getType() === $type && $vehicule->estDisponible()) {
                return $vehicule;
            }
        }
        
        echo"not véhicule{$type}";
    }
    
    public function enregistrerClient(Client $client): void
    {
        $this->clients[] = $client;
        echo "Client enregistr: {$client}";
    }
    
    public function faireReservation(Client $client, Vehicule $vehicule, DateTime $dateDebut, int $nbJours): Reservation
    {
        if (!$vehicule->estDisponible()) {
            echo " réservation not";
        }
        
        
        $reservation = $vehicule->reserve($client, $dateDebut, $nbJours);
        echo "Reserve: {$vehicule} pour {$client} {$client}";
        
        return $reservation;
    }
    
    public function listerVehiculesDisponibles(?string $type = null): array
    {
        $disponibles = [];
        
        foreach ($this->vehicules as $vehicule) {
            if ($vehicule->estDisponible() && ($type === null || $vehicule->getType() === $type)) {
                $disponibles[] = $vehicule;
            }
        }
        
        return $disponibles;
    }
    
    public function afficherInfos(): string
    {
        $nbVehicules = count($this->vehicules);
        $nbClients = count($this->clients);
        
        $vehiculesDisponibles = $this->listerVehiculesDisponibles();
        
        return "Agence {$this->nom} ({$this->ville}) " .
               "Vehicule: {$nbVehicules} {$vehiculesDisponibles}" .
               "Client: {$nbClients}";
    }
    
    public function getNom(): string
    {
        return $this->nom;
    }
    
    public function getVille(): string
    {
        return $this->ville;
    }
    
    public function getVehicules(): array
    {
        return $this->vehicules;
    }
    
    public function getClients(): array
    {
        return $this->clients;
    }
}

class Reservation
{
    protected  $vehicule;
    protected  $client;
    protected $dateDebut;
    protected int $nbJours;
    protected string $status;

    public function __construct(Vehicule $vehicule, Client $client, DateTime $dateDebut, int $nbJours )
    {
        $this->vehicule = $vehicule;
        $this->client = $client;
        $this->dateDebut = $dateDebut;
        $this->nbJours = $nbJours;
        $this->status = 'att';
    }

    public function getVehicule(): Vehicule
    {
        return $this->vehicule;
    }
    
    public function getClient(): Client
    {
        return $this->client;
    }
    
    public function getDateDebut(): DateTime
    {
        return $this->dateDebut;
    }
    
    public function getNbJours(): int
    {
        return $this->nbJours;
    }
    
    public function getStatut(): string
    {
        return $this->status;
    }

    public function calculerMontant(): float
    {
        return $this->vehicule->calculatePrix($this->nbJours);
    }
    
    public function confirmer(): void
    {
        $this->status = 'done';
    }
    public function annuler() {
        $this->status = 'anulle';
    }
}