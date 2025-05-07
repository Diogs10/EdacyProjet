import { UserLogin } from "./user-login";

export interface Login {
    "statu": boolean,
    "token": string,
    "user": UserLogin
}
