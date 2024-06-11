describe('Formulaire de Connexion', () => {
    it('test 1 - generation pdf', () => {
        cy.visit(Cypress.env('app_url') + '/login');

        cy.get('#username').type(Cypress.env('username'));
        cy.get('#password').type(Cypress.env('password'));

        cy.get('button[type="submit"]').click();

        cy.visit(Cypress.env('app_url') + '/membre/pdf/url/create');

        cy.get('#pdf_url_title').type('Document de test');
        cy.get('#pdf_url_url').type('https://www.google.com');

        cy.get('button[type="submit"]').click();

        cy.wait('@fileDownload').then((interception) => {
            expect(interception.response.statusCode).to.equal(200);
        });
    });
});