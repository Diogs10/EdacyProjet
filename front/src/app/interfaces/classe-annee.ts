import { Annee } from "./annee"
import { Classe } from "./classe"

export interface ClasseAnnee {
    "id": number,
    "classe_id": Classe
    "annee_scolaire_id": Annee,
    "effectif": number
}
