CREATE TABLE panier (
    id_panier SERIAL PRIMARY KEY,
    id_client INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_client)
        REFERENCES client(id_client)
);

CREATE TABLE panier_produit (
    id_panier INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,

    PRIMARY KEY (id_panier, id_produit),

    FOREIGN KEY (id_panier)
        REFERENCES panier(id_panier)
        ON DELETE CASCADE,

    FOREIGN KEY (id_produit)
        REFERENCES produit(id_produit)
        ON DELETE CASCADE
);
