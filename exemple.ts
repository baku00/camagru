class Animal {
    taille: number;
    poids: number;
}

class Animal {
    taille: number;
    poids: number;

    manger();
    se_deplacer();
}

class Chien extends Animal {}

class Oiseau extends Animal {}

class Chien extends Animal {
    courir();
}

class Oiseau extends Animal {
    envergure: number;

    voler();
}