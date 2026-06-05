CREATE TABLE panier_produit (
    id_panier INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,

    PRIMARY KEY (id_panier, id_produit),

    FOREIGN KEY (id_panier)
        REFERENCES panier(id_panier),

    FOREIGN KEY (id_produit)
        REFERENCES produit(id_produit)
);