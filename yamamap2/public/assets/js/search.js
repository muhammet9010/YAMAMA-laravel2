
$(function(e) {
    //file export datatable
    var table = $('#example').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'colvis'],
        responsive: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ ',
        }
    });
    table.buttons().container()
        .appendTo('#example_wrapper .col-md-6:eq(0)');

    $('#example1').DataTable({
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_',
        }
    });
    $('#example2').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_',
        }
    });
    var table = $('#example-delete').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_',
        }
    });
    $('#example-delete tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    $('#button').click(function() {
        table.row('.selected').remove().draw(false);
    });

    //Details display datatable
    $('#example-1').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_',
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details for ' + data[0] + ' ' + data[1];
                    }
                }),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                    tableClass: 'table border mb-0'
                })
            }
        }
    });
});




// الخانات الخاصه للجدول
(function (g) {
  "function" === typeof define && define.amd
      ? define(
            ["jquery", "datatables.net", "datatables.net-buttons"],
            function (d) {
                return g(d, window, document);
            }
        )
      : "object" === typeof exports
      ? (module.exports = function (d, f) {
            d || (d = window);
            if (!f || !f.fn.dataTable) f = require("datatables.net")(d, f).$;
            f.fn.dataTable.Buttons || require("datatables.net-buttons")(d, f);
            return g(f, d, d.document);
        })
      : g(jQuery, window, document);
})(function (g, d, f, h) {
  d = g.fn.dataTable;
  g.extend(d.ext.buttons, {
      colvis: function (a, b) {
          return {
              extend: "collection",
              text: function (b) {
                  return b.i18n("buttons.colvis", "Column visibility");
              },
              className: "buttons-colvis",
              buttons: [
                  {
                      extend: "columnsToggle",
                      columns: b.columns,
                      columnText: b.columnText,
                  },
              ],
          };
      },
      columnsToggle: function (a, b) {
          return a
              .columns(b.columns)
              .indexes()
              .map(function (a) {
                  return {
                      extend: "columnToggle",
                      columns: a,
                      columnText: b.columnText,
                  };
              })
              .toArray();
      },
      columnToggle: function (a, b) {
          return {
              extend: "columnVisibility",
              columns: b.columns,
              columnText: b.columnText,
          };
      },
      columnsVisibility: function (a, b) {
          return a
              .columns(b.columns)
              .indexes()
              .map(function (a) {
                  return {
                      extend: "columnVisibility",
                      columns: a,
                      visibility: b.visibility,
                      columnText: b.columnText,
                  };
              })
              .toArray();
      },
      columnVisibility: {
          columns: h,
          text: function (a, b, c) {
              return c._columnText(a, c);
          },
          className: "buttons-columnVisibility",
          action: function (a, b, c, e) {
              a = b.columns(e.columns);
              b = a.visible();
              a.visible(
                  e.visibility !== h ? e.visibility : !(b.length && b[0])
              );
          },
          init: function (a, b, c) {
              var e = this;
              a.on("column-visibility.dt" + c.namespace, function (b, d) {
                  !d.bDestroying &&
                      d.nTable == a.settings()[0].nTable &&
                      e.active(a.column(c.columns).visible());
              }).on("column-reorder.dt" + c.namespace, function () {
                  1 === a.columns(c.columns).count() &&
                      (e.text(c._columnText(a, c)),
                      e.active(a.column(c.columns).visible()));
              });
              this.active(a.column(c.columns).visible());
          },
          destroy: function (a, b, c) {
              a.off("column-visibility.dt" + c.namespace).off(
                  "column-reorder.dt" + c.namespace
              );
          },
          _columnText: function (a, b) {
              var c = a.column(b.columns).index(),
                  e = a
                      .settings()[0]
                      .aoColumns[c].sTitle.replace(/\n/g, " ")
                      .replace(/<br\s*\/?>/gi, " ")
                      .replace(/<select(.*?)<\/select>/g, "")
                      .replace(/<!\-\-.*?\-\->/g, "")
                      .replace(/<.*?>/g, "")
                      .replace(/^\s+|\s+$/g, "");
              return b.columnText ? b.columnText(a, c, e) : e;
          },
      },
      colvisRestore: {
          className: "buttons-colvisRestore",
          text: function (a) {
              return a.i18n("buttons.colvisRestore", "Restore visibility");
          },
          init: function (a, b, c) {
              c._visOriginal = a
                  .columns()
                  .indexes()
                  .map(function (b) {
                      return a.column(b).visible();
                  })
                  .toArray();
          },
          action: function (a, b, c, d) {
              b.columns().every(function (a) {
                  a =
                      b.colReorder && b.colReorder.transpose
                          ? b.colReorder.transpose(a, "toOriginal")
                          : a;
                  this.visible(d._visOriginal[a]);
              });
          },
      },
      colvisGroup: {
          className: "buttons-colvisGroup",
          action: function (a, b, c, d) {
              b.columns(d.show).visible(!0, !1);
              b.columns(d.hide).visible(!1, !1);
              b.columns.adjust();
          },
          show: [],
          hide: [],
      },
  });
  return d.Buttons;
});



// // الطباعه
(function (c) {
  "function" === typeof define && define.amd
      ? define(
            ["jquery", "datatables.net", "datatables.net-buttons"],
            function (f) {
                return c(f, window, document);
            }
        )
      : "object" === typeof exports
      ? (module.exports = function (f, b) {
            f || (f = window);
            if (!b || !b.fn.dataTable) b = require("datatables.net")(f, b).$;
            b.fn.dataTable.Buttons || require("datatables.net-buttons")(f, b);
            return c(b, f, f.document);
        })
      : c(jQuery, window, document);
})(function (c, f, b, n) {
  var i = c.fn.dataTable,
      e = b.createElement("a"),
      m = function (a) {
          e.href = a;
          a = e.host;
          -1 === a.indexOf("/") &&
              0 !== e.pathname.indexOf("/") &&
              (a += "/");
          return e.protocol + "//" + a + e.pathname + e.search;
      };
  i.ext.buttons.print = {
      className: "buttons-print",
      text: function (a) {
          return a.i18n("buttons.print", "Print");
      },
      action: function (a, b, e, h) {
          var a = b.buttons.exportData(
                  c.extend({ decodeEntities: !1 }, h.exportOptions)
              ),
              e = b.buttons.exportInfo(h),
              i = c.map(b.settings()[0].aoColumns, function (b) {
                  return b.sClass;
              }),
              k = function (b, a) {
                  for (var d = "<tr>", c = 0, e = b.length; c < e; c++)
                      d +=
                          "<" +
                          a +
                          " " +
                          (i[c] ? 'class="' + i[c] + '"' : "") +
                          ">" +
                          (null === b[c] || b[c] === n ? "" : b[c]) +
                          "</" +
                          a +
                          ">";
                  return d + "</tr>";
              },
              d = '<table class="' + b.table().node().className + '">';
          h.header && (d += "<thead>" + k(a.header, "th") + "</thead>");
          for (var d = d + "<tbody>", l = 0, o = a.body.length; l < o; l++)
              d += k(a.body[l], "td");
          d += "</tbody>";
          h.footer &&
              a.footer &&
              (d += "<tfoot>" + k(a.footer, "th") + "</tfoot>");
          var d = d + "</table>",
              g = f.open("", "");
          g.document.close();
          var j = "<title>" + e.title + "</title>";
          c("style, link").each(function () {
              var b = j,
                  a = c(this).clone()[0];
              "link" === a.nodeName.toLowerCase() && (a.href = m(a.href));
              j = b + a.outerHTML;
          });
          try {
              g.document.head.innerHTML = j;
          } catch (p) {
              c(g.document.head).html(j);
          }
          g.document.body.innerHTML =
              "<h1>" +
              e.title +
              "</h1><div>" +
              (e.messageTop || "") +
              "</div>" +
              d +
              "<div>" +
              (e.messageBottom || "") +
              "</div>";
          c(g.document.body).addClass("dt-print-view");
          c("img", g.document.body).each(function (b, a) {
              a.setAttribute("src", m(a.getAttribute("src")));
          });
          h.customize && h.customize(g, h, b);
          g.setTimeout(function () {
              h.autoPrint && (g.print(), g.close());
          }, 1e3);
      },
      title: "*",
      messageTop: "*",
      messageBottom: "*",
      exportOptions: {},
      header: !0,
      footer: !1,
      autoPrint: !0,
      customize: null,
  };
  return i.Buttons;
});




o.ext.buttons.excelHtml5 = {
  className: "buttons-excel buttons-html5",
  available: function () {
      return j.FileReader !== s && (v || j.JSZip) !== s && !I() && y;
  },
  text: function (a) {
      return a.i18n("buttons.excel", "Excel");
  },
  action: function (a, b, d, c) {
      this.processing(!0);
      var f = this,
          g = 0,
          a = function (a) {
              return i.parseXML(B[a]);
          },
          e = a("xl/worksheets/sheet1.xml"),
          h = e.getElementsByTagName("sheetData")[0],
          a = {
              _rels: { ".rels": a("_rels/.rels") },
              xl: {
                  _rels: {
                      "workbook.xml.rels": a(
                          "xl/_rels/workbook.xml.rels"
                      ),
                  },
                  "workbook.xml": a("xl/workbook.xml"),
                  "styles.xml": a("xl/styles.xml"),
                  worksheets: { "sheet1.xml": e },
              },
              "[Content_Types].xml": a("[Content_Types].xml"),
          },
          d = b.buttons.exportData(c.exportOptions),
          l,
          m,
          r = function (a) {
              l = g + 1;
              m = q(e, "row", { attr: { r: l } });
              for (var b = 0, d = a.length; b < d; b++) {
                  var f = z(b) + "" + l,
                      j = null;
                  if (null === a[b] || a[b] === s || "" === a[b])
                      if (!0 === c.createEmptyCells) a[b] = "";
                      else continue;
                  var k = a[b];
                  a[b] = i.trim(a[b]);
                  for (var o = 0, r = J.length; o < r; o++) {
                      var p = J[o];
                      if (
                          a[b].match &&
                          !a[b].match(/^0\d+/) &&
                          a[b].match(p.match)
                      ) {
                          j = a[b].replace(/[^\d\.\-]/g, "");
                          p.fmt && (j = p.fmt(j));
                          j = q(e, "c", {
                              attr: { r: f, s: p.style },
                              children: [q(e, "v", { text: j })],
                          });
                          break;
                      }
                  }
                  j ||
                      ("number" === typeof a[b] ||
                      (a[b].match &&
                          a[b].match(/^-?\d+(\.\d+)?$/) &&
                          !a[b].match(/^0\d+/))
                          ? (j = q(e, "c", {
                                attr: { t: "n", r: f },
                                children: [q(e, "v", { text: a[b] })],
                            }))
                          : ((k = !k.replace
                                ? k
                                : k.replace(
                                      /[\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F]/g,
                                      ""
                                  )),
                            (j = q(e, "c", {
                                attr: { t: "inlineStr", r: f },
                                children: {
                                    row: q(e, "is", {
                                        children: {
                                            row: q(e, "t", {
                                                text: k,
                                                attr: {
                                                    "xml:space":
                                                        "preserve",
                                                },
                                            }),
                                        },
                                    }),
                                },
                            }))));
                  m.appendChild(j);
              }
              h.appendChild(m);
              g++;
          };
      i("sheets sheet", a.xl["workbook.xml"]).attr("name", O(c));
      c.customizeData && c.customizeData(d);
      var k = function (a, b) {
              var c = i("mergeCells", e);
              c[0].appendChild(
                  q(e, "mergeCell", {
                      attr: { ref: "A" + a + ":" + z(b) + a },
                  })
              );
              c.attr("count", parseFloat(c.attr("count")) + 1);
              i("row:eq(" + (a - 1) + ") c", e).attr("s", "51");
          },
          p = b.buttons.exportInfo(c);
      p.title && (r([p.title], g), k(g, d.header.length - 1));
      p.messageTop && (r([p.messageTop], g), k(g, d.header.length - 1));
      c.header && (r(d.header, g), i("row:last c", e).attr("s", "2"));
      for (var o = 0, u = d.body.length; o < u; o++) r(d.body[o], g);
      c.footer &&
          d.footer &&
          (r(d.footer, g), i("row:last c", e).attr("s", "2"));
      p.messageBottom &&
          (r([p.messageBottom], g), k(g, d.header.length - 1));
      r = q(e, "cols");
      i("worksheet", e).prepend(r);
      k = 0;
      for (o = d.header.length; k < o; k++)
          r.appendChild(
              q(e, "col", {
                  attr: {
                      min: k + 1,
                      max: k + 1,
                      width: K(d, k),
                      customWidth: 1,
                  },
              })
          );
      c.customize && c.customize(a, c, b);
      0 === i("mergeCells", e).children().length &&
          i("mergeCells", e).remove();
      b = new (v || j.JSZip)();
      d = {
          type: "blob",
          mimeType:
              "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
      };
      A(b, a);
      b.generateAsync
          ? b.generateAsync(d).then(function (a) {
                t(a, p.filename);
                f.processing(false);
            })
          : (t(b.generate(d), p.filename), this.processing(!1));
  },
  filename: "*",
  extension: ".xlsx",
  exportOptions: {},
  header: !0,
  footer: !1,
  title: "*",
  messageTop: "*",
  messageBottom: "*",
  createEmptyCells: !1,
};



l.defaults = {
  buttons: ["copy", "excel", "csv", "pdf", "print"],
  name: "main",
  tabIndex: 0,
  dom: {
      container: { tag: "div", className: "dt-buttons" },
      collection: { tag: "div", className: "dt-button-collection" },
      button: {
          tag: "button",
          className: "dt-button",
          active: "active",
          disabled: "disabled",
      },
      buttonLiner: { tag: "span", className: "" },
  },
};
l.version = "1.5.2";
d.extend(k, {
  collection: {
      text: function (a) {
          return a.i18n("buttons.collection", "Collection");
      },
      className: "buttons-collection",
      action: function (a, b, c, e) {
          var g = d(c).parents("div.dt-button-collection"),
              a = c.position(),
              h = d(b.table().container()),
              f = !1,
              i = c;
          g.length &&
              ((f = d(".dt-button-collection").position()),
              (i = g),
              d("body").trigger("click.dtb-collection"));
          i.parents("body")[0] !== o.body && (i = o.body.lastChild);
          e._collection
              .addClass(e.collectionLayout)
              .css("display", "none")
              .insertAfter(i)
              .fadeIn(e.fade);
          g = e._collection.css("position");
          f && "absolute" === g
              ? e._collection.css({ top: f.top, left: f.left })
              : "absolute" === g
              ? (e._collection.css({
                    top: a.top + c.outerHeight(),
                    left: a.left,
                }),
                (f = h.offset().top + h.height()),
                (f =
                    a.top +
                    c.outerHeight() +
                    e._collection.outerHeight() -
                    f),
                (g = a.top - e._collection.outerHeight()),
                (g = h.offset().top - g),
                (f > g || e.dropup) &&
                    e._collection.css(
                        "top",
                        a.top - e._collection.outerHeight() - 5
                    ),
                (f = a.left + e._collection.outerWidth()),
                (h = h.offset().left + h.width()),
                f > h && e._collection.css("left", a.left - (f - h)),
                (c = c.offset().left + e._collection.outerWidth()),
                c > d(n).width() &&
                    e._collection.css(
                        "left",
                        a.left - (c - d(n).width())
                    ))
              : ((c = e._collection.height() / 2),
                c > d(n).height() / 2 && (c = d(n).height() / 2),
                e._collection.css("marginTop", -1 * c));
          e.background && l.background(!0, e.backgroundClassName, e.fade);
          var j = function () {
              e._collection.fadeOut(e.fade, function () {
                  e._collection.detach();
              });
              d("div.dt-button-background").off("click.dtb-collection");
              l.background(false, e.backgroundClassName, e.fade);
              d("body").off(".dtb-collection");
              b.off("buttons-action.b-internal");
          };
          setTimeout(function () {
              d("div.dt-button-background").on(
                  "click.dtb-collection",
                  function () {}
              );
              d("body")
                  .on("click.dtb-collection", function (a) {
                      var b = d.fn.addBack ? "addBack" : "andSelf";
                      d(a.target).parents()[b]().filter(e._collection)
                          .length || j();
                  })
                  .on("keyup.dtb-collection", function (a) {
                      a.keyCode === 27 && j();
                  });
              if (e.autoClose)
                  b.on("buttons-action.b-internal", function () {
                      j();
                  });
          }, 10);
      },
      background: !0,
      collectionLayout: "",
      backgroundClassName: "dt-button-background",
      autoClose: !1,
      fade: 400,
      attr: { "aria-haspopup": !0 },
  },
  copy: function (a, b) {
      if (k.copyHtml5) return "copyHtml5";
      if (k.copyFlash && k.copyFlash.available(a, b)) return "copyFlash";
  },
  csv: function (a, b) {
      if (k.csvHtml5 && k.csvHtml5.available(a, b)) return "csvHtml5";
      if (k.csvFlash && k.csvFlash.available(a, b)) return "csvFlash";
  },
  excel: function (a, b) {
      if (k.excelHtml5 && k.excelHtml5.available(a, b))
          return "excelHtml5";
      if (k.excelFlash && k.excelFlash.available(a, b))
          return "excelFlash";
  },
  pdf: function (a, b) {
      if (k.pdfHtml5 && k.pdfHtml5.available(a, b)) return "pdfHtml5";
      if (k.pdfFlash && k.pdfFlash.available(a, b)) return "pdfFlash";
  },
  pageLength: function (a) {
      var a = a.settings()[0].aLengthMenu,
          b = d.isArray(a[0]) ? a[0] : a,
          c = d.isArray(a[0]) ? a[1] : a,
          e = function (a) {
              return a.i18n(
                  "buttons.pageLength",
                  { "-1": "Show all rows", _: "Show %d rows" },
                  a.page.len()
              );
          };
      return {
          extend: "collection",
          text: e,
          className: "buttons-page-length",
          autoClose: !0,
          buttons: d.map(b, function (a, b) {
              return {
                  text: c[b],
                  className: "button-page-length",
                  action: function (b, c) {
                      c.page.len(a).draw();
                  },
                  init: function (b, c, e) {
                      var d = this,
                          c = function () {
                              d.active(b.page.len() === a);
                          };
                      b.on("length.dt" + e.namespace, c);
                      c();
                  },
                  destroy: function (a, b, c) {
                      a.off("length.dt" + c.namespace);
                  },
              };
          }),
          init: function (a, b, c) {
              var d = this;
              a.on("length.dt" + c.namespace, function () {
                  d.text(e(a));
              });
          },
          destroy: function (a, b, c) {
              a.off("length.dt" + c.namespace);
          },
      };
  },
});
