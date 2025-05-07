import { Module } from "./module"
import { Professeur } from "./professeur"

export interface ModuleProf {
    "module": Module,
    "professeur": Professeur
}
