// Generate username
let username = 'user' + Math.random().toString(36).substring(7);

describe('Formulaire de d\'inscription', () => {
    it('test 1 - inscription OK', () => {
        cy.visit(Cypress.env('app_url') + '/register');

        cy.get('#registration_form_email').type(username + '@example.com');
        cy.get('#registration_form_plainPassword_first').type('password');
        cy.get('#registration_form_plainPassword_second').type('password');
        cy.get('#registration_form_firstName').type('Firstname');
        cy.get('#registration_form_lastName').type('Lastname');

        cy.get('button[type="submit"]').click();

        cy.contains('Vous êtes connecté en tant que Firstname Lastname').should('exist');
    });

    it('test 2 - inscription KO', () => {
        cy.visit(Cypress.env('app_url') + '/register');

        cy.get('#registration_form_email').type(username + '@example.com');
        cy.get('#registration_form_plainPassword_first').type('password1');
        cy.get('#registration_form_plainPassword_second').type('password2');
        cy.get('#registration_form_firstName').type('Firstname');
        cy.get('#registration_form_lastName').type('Lastname');

        cy.get('button[type="submit"]').click();

        cy.contains('There is already an account with this email').should('exist');
    });
});