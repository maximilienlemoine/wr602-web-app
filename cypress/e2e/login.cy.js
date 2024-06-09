describe('Formulaire de Connexion', () => {
    it('test 1 - connexion OK', () => {
        cy.visit(Cypress.env('app_url') + '/login');

        cy.get('#username').type(Cypress.env('username'));
        cy.get('#password').type(Cypress.env('password'));

        cy.get('button[type="submit"]').click();

        cy.contains('Vous êtes connecté en tant que').should('exist');
    });

    it('test 2 - connexion KO', () => {
        cy.visit(Cypress.env('app_url') + '/login');

        cy.get('#username').type(Cypress.env('username'));
        cy.get('#password').type('WRONG_PASSWORD');

        cy.get('button[type="submit"]').click();

        cy.contains('Invalid credentials.').should('exist');
    });
});