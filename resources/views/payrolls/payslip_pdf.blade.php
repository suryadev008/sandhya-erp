<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payslip – {{ $employee->name }} – {{ $monthName }} {{ $payroll->year }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }

    .page { padding: 20px 25px; }

    /* Header */
    .header { border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 12px; }
    .header .company-name { font-size: 18px; font-weight: bold; color: #2c3e50; }
    .header .slip-title   { font-size: 13px; color: #666; margin-top: 2px; }

    /* Info row */
    .info-table { width: 100%; margin-bottom: 12px; }
    .info-table td { padding: 3px 6px; }
    .info-table .label { color: #666; width: 130px; }
    .info-table .value { font-weight: bold; }

    /* Section heading */
    .section-heading {
      background: #2c3e50; color: #fff;
      padding: 4px 8px; font-size: 11px;
      font-weight: bold; margin-bottom: 0;
    }

    /* Entry table */
    .entry-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    .entry-table th {
      background: #ecf0f1; padding: 4px 6px;
      border: 1px solid #ccc; font-size: 10px; text-align: left;
    }
    .entry-table td { padding: 3px 6px; border: 1px solid #ddd; font-size: 10px; }
    .entry-table .day-subtotal { background: #f8f9fa; font-style: italic; color: #666; }
    .entry-table tfoot td { background: #ecf0f1; font-weight: bold; border: 1px solid #ccc; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    /* Summary box */
    .summary-box { width: 240px; float: right; margin-top: 5px; }
    .summary-box table { width: 100%; border-collapse: collapse; }
    .summary-box table td { padding: 5px 8px; border: 1px solid #ddd; font-size: 11px; }
    .summary-box .row-lathe  { background: #e8f4f8; }
    .summary-box .row-extra  { background: #e8f8ee; }
    .summary-box .row-deduct { background: #fdecea; }
    .summary-box .row-net    { background: #2c3e50; color: #fff; font-weight: bold; font-size: 13px; }

    /* Extra / deduction tables */
    .side-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .side-table th { background: #ecf0f1; padding: 3px 6px; border: 1px solid #ccc; font-size: 10px; }
    .side-table td { padding: 3px 6px; border: 1px solid #ddd; font-size: 10px; }

    /* Footer */
    .footer { margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px; }
    .footer .sign-col { width: 33%; display: inline-block; text-align: center; }

    .clearfix::after { content: ''; display: table; clear: both; }

    .badge { padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
    .badge-paid     { background: #28a745; color: #fff; }
    .badge-approved { background: #17a2b8; color: #fff; }
    .badge-draft    { background: #6c757d; color: #fff; }
  </style>
</head>
<body>
<div class="page">

  {{-- ── Header ──────────────────────────────── --}}
  <div class="header">
    <div style="float:left;">
      <div class="company-name">{{ config('app.name') }}</div>
      <div class="slip-title">SALARY SLIP — {{ strtoupper($monthName) }} {{ $payroll->year }}</div>
    </div>
    <div style="float:right; text-align:right;">
      @php $statusColors = ['draft'=>'draft','approved'=>'approved','paid'=>'paid']; @endphp
      <span class="badge badge-{{ $statusColors[$payroll->status] ?? 'draft' }}">
        {{ strtoupper($payroll->status) }}
      </span>
      <div style="margin-top:4px; font-size:10px; color:#888;">
        Generated: {{ now()->format('d M Y') }}
      </div>
    </div>
    <div style="clear:both;"></div>
  </div>

  {{-- ── Employee Info ────────────────────────── --}}
  <table class="info-table" style="margin-bottom:14px;">
    <tr>
      <td class="label">Employee Code</td>
      <td class="value">{{ $employee->emp_code }}</td>
      <td class="label">Name</td>
      <td class="value">{{ $employee->name }}</td>
    </tr>
    <tr>
      <td class="label">Employee Type</td>
      <td class="value">{{ ucfirst($employee->employee_type) }}</td>
      <td class="label">Pay Period</td>
      <td class="value">{{ $monthName }} {{ $payroll->year }}</td>
    </tr>
    @if($salary)
    <tr>
      <td class="label">Per Day Rate</td>
      <td class="value">₹ {{ number_format($salary->per_day, 2) }}</td>
      <td class="label">Per Month Rate</td>
      <td class="value">₹ {{ number_format($salary->per_month, 2) }}</td>
    </tr>
    @endif
    @if($employee->bank_account_no)
    <tr>
      <td class="label">Bank Account</td>
      <td class="value">{{ $employee->bank_account_no }}</td>
      <td class="label">Bank Name</td>
      <td class="value">{{ $employee->bank_name ?? '—' }}</td>
    </tr>
    @endif
  </table>

  {{-- ── Production Entries ───────────────────── --}}
  <div class="section-heading">LATHE PRODUCTION DETAILS</div>
  @if($entries->isEmpty())
    <p style="padding:6px; color:#888; font-style:italic;">No production entries for this month.</p>
  @else
    <table class="entry-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Vendor</th>
          <th>Part No.</th>
          <th>Operation</th>
          <th>Shift</th>
          <th class="text-right">Qty</th>
          <th class="text-right">Rate (₹)</th>
          <th class="text-right">Amount (₹)</th>
        </tr>
      </thead>
      <tbody>
        @php $sno = 1; @endphp
        @foreach($entriesByDate as $date => $dayEntries)
          @foreach($dayEntries as $entry)
            <tr>
              <td>{{ $sno++ }}</td>
              <td class="text-nowrap">{{ \Carbon\Carbon::parse($date)->format('d M') }}</td>
              <td>{{ $entry->company?->company_name ?? '—' }}</td>
              <td>{{ $entry->part?->part_number ?? '—' }}</td>
              <td>{{ $entry->operation?->operation_name ?? '—' }}</td>
              <td style="text-transform:capitalize;">{{ $entry->shift }}</td>
              <td class="text-right">{{ $entry->qty }}</td>
              <td class="text-right">{{ number_format($entry->rate, 2) }}</td>
              <td class="text-right">{{ number_format($entry->amount, 2) }}</td>
            </tr>
          @endforeach
          <tr class="day-subtotal">
            <td colspan="6" class="text-right">{{ \Carbon\Carbon::parse($date)->format('d M Y') }} — Subtotal</td>
            <td class="text-right">{{ $dayEntries->sum('qty') }}</td>
            <td></td>
            <td class="text-right">{{ number_format($dayEntries->sum('amount'), 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8" class="text-right">Total Lathe Earnings</td>
          <td class="text-right">{{ number_format($payroll->total_lathe_amount, 2) }}</td>
        </tr>
      </tfoot>
    </table>
  @endif

  {{-- ── Extra Payments & Deductions ─────────────────── --}}
  <div class="clearfix">
    <div style="float:left; width:55%;">
      @if($payroll->extraPayments->count())
        <div class="section-heading" style="margin-bottom:4px;">EXTRA PAYMENTS</div>
        <table class="side-table">
          <thead>
            <tr><th>#</th><th>Description</th><th class="text-right">Amount (₹)</th></tr>
          </thead>
          <tbody>
            @foreach($payroll->extraPayments as $i => $ep)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $ep->payment_name }}</td>
                <td class="text-right">{{ number_format($ep->amount, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif

      @if($payroll->deductions > 0)
        <div class="section-heading" style="margin-bottom:4px; background:#c0392b;">DEDUCTIONS</div>
        <table class="side-table">
          <tbody>
            <tr>
              <td>{{ $payroll->deduction_remarks ?: 'Deduction' }}</td>
              <td class="text-right" style="color:#c0392b;">{{ number_format($payroll->deductions, 2) }}</td>
            </tr>
          </tbody>
        </table>
      @endif
    </div>

    {{-- Summary --}}
    <div class="summary-box">
      <div class="section-heading" style="margin-bottom:4px;">SALARY SUMMARY</div>
      <table>
        <tr class="row-lathe">
          <td>Lathe Earnings</td>
          <td class="text-right">₹ {{ number_format($payroll->total_lathe_amount, 2) }}</td>
        </tr>
        @if($payroll->extra_payment_total > 0)
        <tr class="row-extra">
          <td>Extra Payments</td>
          <td class="text-right">+ ₹ {{ number_format($payroll->extra_payment_total, 2) }}</td>
        </tr>
        @endif
        <tr>
          <td><strong>Gross Amount</strong></td>
          <td class="text-right"><strong>₹ {{ number_format($payroll->gross_amount, 2) }}</strong></td>
        </tr>
        @if($payroll->deductions > 0)
        <tr class="row-deduct">
          <td>Deductions</td>
          <td class="text-right">- ₹ {{ number_format($payroll->deductions, 2) }}</td>
        </tr>
        @endif
        <tr class="row-net">
          <td>NET PAYABLE</td>
          <td class="text-right">₹ {{ number_format($payroll->net_amount, 2) }}</td>
        </tr>
      </table>
    </div>
  </div>

  {{-- ── Footer / Signatures ─────────────────── --}}
  <div class="footer" style="margin-top:30px;">
    <table style="width:100%;">
      <tr>
        <td style="width:33%; text-align:center; padding-top:25px; border-top:1px solid #999;">
          Employee Signature
        </td>
        <td style="width:33%; text-align:center; padding-top:25px; border-top:1px solid #999;">
          Prepared By
        </td>
        <td style="width:33%; text-align:center; padding-top:25px; border-top:1px solid #999;">
          Authorised Signatory
        </td>
      </tr>
    </table>
    <div style="text-align:center; margin-top:12px; color:#aaa; font-size:9px;">
      This is a computer generated payslip. — {{ config('app.name') }}
    </div>
  </div>

</div>
</body>
</html>
