# Test technique - Maison Despriet

Créer un système de remises & de marges applicables à une commande client :

## Cas à couvrir :

- Clients normaux : pas de remise ni marge globale de base ;
- Clients VIP : remise globale de 10% non-cumulable sur le prix de vente de tous les articles ;
- Clients grossistes : marge globale de 30% sur le prix d'achat de tous les articles ;
- Catégorie de produits "surgelés" : remise de 5% sur les prix de vente des articles concernés qui ne s'applique pas aux grossistes ;
- Catégorie de produits "promotions" : remise de 15% sur les prix de vente des articles concernés qui ne s'applique pas aux grossistes.

## Consignes :

1. Lorsque plusieurs remises peuvent s'appliquer pour un même produit, c'est uniquement la plus avantageuse qui est reprise
2. Les remises s'affichent dans le panier en une seule ligne, affichée en dernière position avant le total de la commande, sous forme "Remises: - X€" avec un style visuel différent (bonus : créer un composant Vue.js spécifique pour cet affichage), les marges ne sont pas affichées car elles sont directement incluses dans les prix affichés.
3. Écrire des tests pour garantir la fonctionnalité d'application des remises/marges : utilisez phpunit ou pest selon vos préférences et testez au minimum tous les cas à couvrir (listés ci-dessous) en analysant les résultats possibles de requêtes POST sur l’URL d’ajout au panier (bonus si vérification des éléments modifiés/ajoutés en base de données après l’exécution de la requête).

