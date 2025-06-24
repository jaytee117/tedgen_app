import './bootstrap';
import DataTable from 'datatables.net-dt';
function initCustomerTable() {
    let table = new DataTable('#customerTable', {
        "ordering": false,
        "sDom": '<"top"f<"controls"><"clear">>rt<"bottom"ilp><"clear">',

        fnInitComplete: function () {
            $('.dt-search input').attr("placeholder", "Search");

        },
        oLanguage: {
            "sSearch": "_INPUT_" //search
        }
    });
}
window.initCustomerTable = initCustomerTable;

function initSiteTable() {
    let table = new DataTable('#siteTable', {
        "ordering": false,
        "sDom": '<"top"f<"controls"><"clear">>rt<"bottom"ilp><"clear">',

        fnInitComplete: function () {
            $('.dt-search input').attr("placeholder", "Search");

        },
        oLanguage: {
            "sSearch": "_INPUT_" //search
        }
    });
}
window.initSiteTable = initSiteTable;

function initInstallationTable() {
    let table = new DataTable('#installationTable', {
        "ordering": false,
        "sDom": '<"top"f<"controls"><"clear">>rt<"bottom"ilp><"clear">',

        fnInitComplete: function () {
            $('.dt-search input').attr("placeholder", "Search");

        },
        oLanguage: {
            "sSearch": "_INPUT_" //search
        }
    });
}
window.initInstallationTable = initInstallationTable;

