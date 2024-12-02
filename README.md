# Infra AWS CICD

## Responsabilité partagé

AWS utilise un [Modele de responsabilité partagée](https://aws.amazon.com/fr/compliance/shared-responsibility-model/) pour l'utilisation de ses infrastructure.

## Services utilisés

- VPC (réseau)
- RDS Aurora (base de données)
- S3 (stockage fichiers)
- EC2 / ECS (machines physique / cluster de gestion de container)
- ECR (registry)

## Région et AZ

AWS offre une couverture mondiale pour l'ensemble de ses services.
Nous privilégions un hébergement sur une région européenne avec au moins 3 zones de disponibilité, à savoir :

- Paris
- Irlande
- Francfort
- Londres

La région est à définir avec le client  
https://aws.amazon.com/fr/about-aws/global-infrastructure/

### Infrastructure Réseau

#### VPC, Sous-réseau

1 VPC - 10.20.0.0/16  
3 sous-réseau par client (1 par AZ) : masque réseau en /21

#### ACL, security-group

Chaque groupe de 3 sous-réseau appartient à 1 ACL restreignant le trafic entrant à ces seuls sous-réseaux et services extérieurs nécessaires.
A celà se rajoute les security-group qui vont restreindre le trafic de chaque hote EC2

#### VPN

IPsec
OpenVPN

### Logiciel applicatif

- Docker
- Apache
- Mysecureshell
- NodeJS
- ProFTP

### RPO / RTO

AWS fournit une infrastructure hautement disponible qui permet à CICD de concevoir des applications résilientes et de réagir rapidement aux incidents majeurs ou aux scénarios de sinistre.  
CICD se base sur les systèmes AWS permettant une haute disponibilité (RDS Multi AZ + ECS cluster Multi AZ).  
La sauvegarde des bases de données avec AWS permet une restauration "Restore To Point In Time" permettant ainsi de restaurer une base de données rapidement à un instant précis.
http://docs.aws.amazon.com/fr_fr/AmazonRDS/latest/UserGuide/USER_PIT.html
L'intervalle par défaut est configuré à une sauvegarde par jour avec 30 jours de rétentions.  
De plus, une sauvegarde par mois sera envoyée sur le service S3 Glacier pour une durée de 12 mois.

Ces intervalles sont à confirmer avec le client.

### Fenetre maintenance

Une fenêtre de maintenance est nécessaire au service.
Les instances pourront pendant ces plages effectuer les mises à jour nécessaire à la sécurité du système.  
Cette plage sera à définir avec le client.
http://docs.aws.amazon.com/fr_fr/AmazonRDS/latest/UserGuide/USER_UpgradeDBInstance.Maintenance.html

### Structure applicative

1. ELB (Proxy / loadbalncer)
2. ECS (cluster EC2 hébergeant les containers)
3. Apache / NodeJS (serveur web, notre application)
4. RDS (base de données)
5. S3 (stockage fichiers)

### Processus de déploiement

1. Gitlab
   > - build
   > - test
   > - stage
   > - deploy
2. ECR
3. ECS

### Supervision / Monitoring

- Cloudwatch
- CloudTrail

### Env. de Test

### Env. de Dev.

- VSCode
- Gitlab
- SourceTree
- Docker

### Processus de Dev.

### Règle de gestion

#### Clés RSA

Une clé par client stockée sur un bucket protégé S3 réservé aux admins.

#### Security group

tag name : client en majuscule
tag group name : app-service en minuscule

#### Role IAM

client-app

#### S3

1 bucket par app avec dossier preview et dossier prod
# aws
# aws
