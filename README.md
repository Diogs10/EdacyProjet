# 📚 Projet – Gestion Pédagogique Sonatel Academy

Application web et mobile pour la **planification des cours** et la **gestion des absences** à la Sonatel Academy, développée en **Laravel (Backend)**, **Angular (Web Frontend)** et **Flutter (Mobile Frontend)**.

## 🔧 Technologies utilisées

- **Backend** : Laravel 10+, PHP 8.1+
- **Frontend Web** : Angular 15+
- **Mobile** : Flutter
- **Base de données** : MySQL
- **API RESTful**
- **Authentification JWT / Sanctum**
- **Gestion des fichiers Excel (importation)**

---

## 🧩 Modules fonctionnels

### 1. Planification des Cours
- Gestion des années scolaires, classes, salles, semestres, modules.
- Ajout de professeurs.
- Planification des cours (quotas horaires, modules, classes, professeurs).
- Planification des sessions de cours (date, heure, salle si présentiel).
- Importation des étudiants via Excel.
- Suivi et filtrage des cours planifiés par état (En cours, Terminé).

### 2. Gestion des Absences
- Feuilles d’émargement disponibles 30 minutes après le début du cours.
- Validation des présences par l’attaché pédagogique.
- Visualisation des absences et heures manquées.
- Justification des absences par les étudiants (traitées par l’attaché).
- Notifications d’avertissement à 10h / convocation à 20h d’absence.

---

## 👥 Rôles utilisateurs

- **Responsable Pédagogique (RP)** : Angular
- **Professeur** : Angular
- **Étudiant** : Flutter
- **Attaché pédagogique** : Angular

---

## 🚀 Initialisation du projet

1. **Cloner le projet**
   ```bash
   git clone https://github.com/Diogs10/EdacyProjet.git
2. **Acceder au back**
   cd back
   et vous y trouverez un fichier README pour bien démarrer le projet;
3. **Acceder au front**
   cd front
   et vous y trouverez un fichier README pour bien démarrer le projet;
4. **Format fichier excel**
Ce fichier excel est le format qu'on attend pour la partie inscription des étudiants
Lien du vidéo démo : https://www.awesomescreenshot.com/video/39558970?key=7df82bd37b6cce4f324b257b4529a058