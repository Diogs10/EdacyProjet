import { Cours } from "./cours";
import { Salle } from "./salle";

export interface Session {
    "id":number,
    "cours_id":Cours,
    "salle_id":Salle,
    "heureDebut": string,
    "duree": number,
    "date": Date,
    "etat": string
}
