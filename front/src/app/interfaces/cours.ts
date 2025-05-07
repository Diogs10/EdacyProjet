import { ClasseAnnee } from "./classe-annee"
import { ModuleProf } from "./module-prof"
import { Semestre } from "./semestre"

export interface Cours {
    "id": number,
    "heureTotal": number,
    "semestre_id": Semestre
    "module_prof_id": ModuleProf
    "termine": string,
    "classes": ClasseAnnee[]
}
