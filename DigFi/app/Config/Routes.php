<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::landingPage');
$routes->get('dashboard', 'Home::index');
$routes->post('difi-authenticate/difi-login-attempt', 'AuthController::login');
$routes->get('difi-authenticate/difi-logout', 'AuthController::logout');

## User Management Routes
$routes->get('user-manager', 'UserManager::index');
$routes->post('user-manager/save', 'UserManager::saveUser');
$routes->post('user-manager/delete', 'UserManager::deleteUser');
$routes->get('difi-account-management/difi-change-password', 'UserManager::mandatoryPassChange');
$routes->post('difi-account-management/difi-save-new-password', 'UserManager::changePassword');
$routes->get('user-manager/createAdmin', 'UserManager::createAdminUser');
$routes->get('user-manager/fetch-rights', 'UserManager::loadRightsMenus');
$routes->post('user-manager/save-role-rights', 'UserManager::saveRights');

## User Role Management Routes
$routes->get('user-role-manager', 'UserManager::listUserRoles');
$routes->post('user-role-manager/save-role', 'UserManager::saveUserRole');
$routes->post('user-role-manager/delete', 'UserManager::deleteUserRole');
$routes->post('user-role-mananger/role-menus', 'UserManager::roleMenus');

##System Setup Routes
#loans
$routes->get('setup/loans', 'Settings::loansIndex');
$routes->post('setup/loans/save', 'Settings::saveLoanProduct');
$routes->post('setup/loans/delete', 'Settings::deleteLoanProduct');
$routes->post('setup/loans/retire', 'Settings::retireLoanProduct');

#Account Types
$routes->get('setup/account-types', 'Settings::accountTypesIndex');
$routes->post('setup/account-type/save', 'Settings::saveAccountType');
$routes->post('setup/account-type/delete', 'Settings::deleteAccountType');
$routes->post('setup/account-types/retire', 'Settings::retireAccountType');

#Account Natures
$routes->get('setup/account-nature', 'Settings::accountNaturesIndex');
$routes->post('setup/account-nature/save', 'Settings::saveAccountNature');
$routes->post('setup/account-nature/delete', 'Settings::deleteAccountNature');
$routes->post('setup/account-nature/retire', 'Settings::retireAccountNature');
$routes->post('setup/account-nature/edit', 'Settings::editAccountNature');

#Identification Types
$routes->get('setup/identification-Types', 'Settings::identificationTypesIndex');
$routes->post('setup/identification-Types/save', 'Settings::saveIdentificationType');
$routes->post('setup/identification-Types/delete', 'Settings::deleteIdentificationType');
$routes->post('setup/identification-Types/retire', 'Settings::retireIdentificationType');

#setup/transaction-types
$routes->get('setup/transaction-types', 'Settings::transactionTypesIndex');
$routes->post('setup/transaction-types/save', 'Settings::saveTransactionType');
$routes->post('setup/transaction-types/delete', 'Settings::deleteTransactionType');
$routes->post('setup/transaction-types/retire', 'Settings::retireTransactionType');

#setup/transaction-methods
$routes->get('setup/transaction-methods', 'Settings::transactionMethodsIndex');
$routes->post('setup/transaction-methods/save', 'Settings::saveTransactionMethod');
$routes->post('setup/transaction-methods/delete', 'Settings::deleteTransactionMethod');
$routes->post('setup/transaction-methods/retire', 'Settings::retireTransactionMethod');

#transaction method providers
$routes->post('setup/transaction-method-providers', 'Settings::getProviderList');
$routes->post('setup/transaction-method-provider/save', 'Settings::saveProvider');
$routes->post('setup/transaction-method-provider/change-status', 'Settings::changeProviderStatus');


#customer & Accounts Management
$routes->get('customer/index','CustomerManager::customerIndex');
$routes->get('customer/customer-registration','CustomerManager::customerRegistration');
$routes->get('customer/customer-registration/edit/(:num)','CustomerManager::customerRegistration');
$routes->post('customer/customer-save', 'CustomerManager::saveCustomer');
$routes->post('customer/delete-customer', 'CustomerManager::deleteCustomer');

$routes->get('customer/accounts', 'CustomerManager::accountIndex');
$routes->get('customer/list-customers', 'CustomerManager::getCustomerList');
$routes->get('customer/accounts/edit/(:num)', 'CustomerManager::createEditAccount');
$routes->get('customer/create-edit-account', 'CustomerManager::createEditAccount');
$routes->post('customer/save-account', 'CustomerManager::saveAccount');
$routes->post('customer/change-account-status', 'CustomerManager::changeAccountStatus');


$routes->get('loans/loan-applications', 'FinanceController::loanApplications');
$routes->get('loans/loan-details/(:num)', 'FinanceController::loanDetails');
$routes->get('loans/apply-loan', 'FinanceController::applyLoan');
$routes->get('loans/apply-loan/(:num)', 'FinanceController::applyLoan');
$routes->post('loans/loan-save', 'FinanceController::saveLoanApplication');
$routes->post('loans/loan-approve', 'FinanceController::approveLoan');
$routes->post('loans/loan-reject', 'FinanceController::rejectLoan');



