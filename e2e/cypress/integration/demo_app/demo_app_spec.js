describe('login ', () => {
    it('Login Ui Test ', () => {
        const loginUrl = `${Cypress.env("baseUrl")}/index/login`;
        cy.visit(loginUrl);
        cy.wait('1000');
        cy.get('h2').contains('Login');
        cy.get('input[name="email"]').type('demo@billbox.com');
        cy.get('input[name="password"]').type('12345');
        cy.get('button').contains('Login').click();
    });  
  });
  